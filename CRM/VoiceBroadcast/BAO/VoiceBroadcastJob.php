<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/26/14
 * Time: 11:48 AM
 */



namespace CRM\VoiceBroadcast\BAO;

class VoiceBroadcastJob
{
    CONST MAX_CONTACTS_TO_PROCESS = 1000;


  // before we run jobs, we need to split the jobs
  public static function runJobs_pre($offset = 200, $mode = NULL, $em = null)
  {
    $job          = new VoiceBroadcastJob();
    $config       = CRM_Core_Config::singleton();
    $currentTime  = date('YmdHis');
    //$mailingACL = CRM_Mailing_BAO_Mailing::mailingACL('m');
    $domainID     = CRM_Core_Config::domainID();



    // Select all the voice broadcast jobs that are created from
    // when the voice broadcast is submitted or scheduled.
    $query = "SELECT j.*
              FROM civicrm_voice_broadcast_job j,
                   civicrm_voice_broadcast m
              WHERE m.id = j.mailing_id AND m.domain_id = {$domainID}
                AND   j.is_test = 0
                AND   ( ( j.start_date IS null
                AND       j.scheduled_date <= $currentTime
                AND       j.status = 'Scheduled'
                AND       j.end_date IS null ) )
                AND ((j.job_type is NULL) OR (j.job_type <> 'child'))
              ORDER BY j.scheduled_date,
                       j.start_date";


    $job->query($query);


    // For each of the "Parent Jobs" we find, we split them into
    // X Number of child jobs
    while ($job->fetch()) {

      // still use job level lock for each child job
      $lockName = "civivoicebroadcast.job.{$job->id}";

      $lock = new CRM_Core_Lock($lockName);
      if (!$lock->isAcquired()) {
        continue;
      }

      // Re-fetch the job status in case things
      // changed between the first query and now
      // to avoid race conditions
      $job->status = CRM_Core_DAO::getFieldValue(
        'CRM_Mailing_DAO_MailingJob',
        $job->id,
        'status',
        'id',
        TRUE
      );
      if ($job->status != 'Scheduled') {
        $lock->release();
        continue;
      }

      $job->split_job($offset);

      // update the status of the parent job
      $transaction = new CRM_Core_Transaction();

      $saveJob             = new CRM_Mailing_DAO_MailingJob();
      $saveJob->id         = $job->id;
      $saveJob->start_date = date('YmdHis');
      $saveJob->status     = 'Running';
      $saveJob->save();

      $transaction->commit();

      // Release the job lock
      $lock->release();
    }
  }



  /**
   * Initiate all pending/ready jobs
   *
   * @return void
   * @access public
   * @static
   */
  public static function runJobs($testParams = NULL, $mode = NULL) {
    $job = new CRM_Mailing_BAO_MailingJob();

    $config       = CRM_Core_Config::singleton();
    $jobTable     = CRM_Mailing_DAO_MailingJob::getTableName();
    $mailingTable = CRM_Mailing_DAO_Mailing::getTableName();

    if (!empty($testParams)) {
      $query = "
      SELECT *
        FROM $jobTable
       WHERE id = {$testParams['job_id']}";
      $job->query($query);
    }
    else {
      $currentTime = date('YmdHis');
      $mailingACL  = CRM_Mailing_BAO_Mailing::mailingACL('m');
      $domainID    = CRM_Core_Config::domainID();

      $modeClause = 'AND m.sms_provider_id IS NULL';
      if ($mode == 'sms') {
        $modeClause = 'AND m.sms_provider_id IS NOT NULL';
      }

      // Select the first child job that is scheduled
      // CRM-6835
      $query = "
      SELECT   j.*
        FROM   $jobTable     j,
           $mailingTable m
       WHERE   m.id = j.mailing_id AND m.domain_id = {$domainID}
                     {$modeClause}
         AND   j.is_test = 0
         AND   ( ( j.start_date IS null
         AND       j.scheduled_date <= $currentTime
         AND       j.status = 'Scheduled' )
                OR     ( j.status = 'Running'
         AND       j.end_date IS null ) )
         AND (j.job_type = 'child')
         AND   {$mailingACL}
      ORDER BY j.mailing_id,
           j.id
      ";

      $job->query($query);
    }


    while ($job->fetch()) {
      // still use job level lock for each child job
      $lockName = "civimail.job.{$job->id}";

      $lock = new CRM_Core_Lock($lockName);
      if (!$lock->isAcquired()) {
        continue;
      }

      // for test jobs we do not change anything, since its on a short-circuit path
      if (empty($testParams)) {
        // we've got the lock, but while we were waiting and processing
        // other emails, this job might have changed under us
        // lets get the job status again and check
        $job->status = CRM_Core_DAO::getFieldValue(
          'CRM_Mailing_DAO_MailingJob',
          $job->id,
          'status',
          'id',
          TRUE
        );

        if (
          $job->status != 'Running' &&
          $job->status != 'Scheduled'
        ) {
          // this includes Cancelled and other statuses, CRM-4246
          $lock->release();
          continue;
        }
      }

      /* Queue up recipients for the child job being launched */

      if ($job->status != 'Running') {
        $transaction = new CRM_Core_Transaction();

        // have to queue it up based on the offset and limits
        // get the parent ID, and limit and offset
        $job->queue($testParams);

        // Mark up the starting time
        $saveJob             = new CRM_Mailing_DAO_MailingJob();
        $saveJob->id         = $job->id;
        $saveJob->start_date = date('YmdHis');
        $saveJob->status     = 'Running';
        $saveJob->save();

        $transaction->commit();
      }

      // Get the mailer
      // make it a persistent connection, CRM-9349
      if ($mode === NULL) {
        $mailer = $config->getMailer(TRUE);
      }
      elseif ($mode == 'sms') {
        $mailer = CRM_SMS_Provider::singleton(array('mailing_id' => $job->mailing_id));
      }

      // Compose and deliver each child job
      $isComplete = $job->deliver($mailer, $testParams);

      CRM_Utils_Hook::post('create', 'CRM_Mailing_DAO_Spool', $job->id, $isComplete);

      // Mark the child complete
      if ($isComplete) {
        /* Finish the job */

        $transaction = new CRM_Core_Transaction();

        $saveJob           = new CRM_Mailing_DAO_MailingJob();
        $saveJob->id       = $job->id;
        $saveJob->end_date = date('YmdHis');
        $saveJob->status   = 'Complete';
        $saveJob->save();

        $transaction->commit();

        // don't mark the mailing as complete
      }

      // Release the child joblock
      $lock->release();

      if ($testParams) {
        return $isComplete;
      }
    }
  }

  // post process to determine if the parent job
  // as well as the mailing is complete after the run
  public static function runJobs_post($mode = NULL) {

    $job = new CRM_Mailing_BAO_MailingJob();

    $mailing = new CRM_Mailing_BAO_Mailing();

    $config       = CRM_Core_Config::singleton();
    $jobTable     = CRM_Mailing_DAO_MailingJob::getTableName();
    $mailingTable = CRM_Mailing_DAO_Mailing::getTableName();

    $currentTime = date('YmdHis');
    $mailingACL  = CRM_Mailing_BAO_Mailing::mailingACL('m');
    $domainID    = CRM_Core_Config::domainID();

    $query = "
                SELECT   j.*
                  FROM   $jobTable     j,
                                 $mailingTable m
                 WHERE   m.id = j.mailing_id AND m.domain_id = {$domainID}
                   AND   j.is_test = 0
                   AND       j.scheduled_date <= $currentTime
                   AND       j.status = 'Running'
                   AND       j.end_date IS null
                   AND       (j.job_type != 'child' OR j.job_type is NULL)
                ORDER BY j.scheduled_date,
                                 j.start_date";

    $job->query($query);

    // For each parent job that is running, let's look at their child jobs
    while ($job->fetch()) {

      $child_job = new CRM_Mailing_BAO_MailingJob();

      $child_job_sql = "
            SELECT count(j.id)
                        FROM civicrm_mailing_job j, civicrm_mailing m
                        WHERE m.id = j.mailing_id
                        AND j.job_type = 'child'
                        AND j.parent_id = %1
            AND j.status <> 'Complete'";
      $params = array(1 => array($job->id, 'Integer'));

      $anyChildLeft = CRM_Core_DAO::singleValueQuery($child_job_sql, $params);

      // all of the child jobs are complete, update
      // the parent job as well as the mailing status
      if (!$anyChildLeft) {

        $transaction = new CRM_Core_Transaction();

        $saveJob           = new CRM_Mailing_DAO_MailingJob();
        $saveJob->id       = $job->id;
        $saveJob->end_date = date('YmdHis');
        $saveJob->status   = 'Complete';
        $saveJob->save();

        $mailing->reset();
        $mailing->id = $job->mailing_id;
        $mailing->is_completed = TRUE;
        $mailing->save();
        $transaction->commit();
      }
    }
  }
} 