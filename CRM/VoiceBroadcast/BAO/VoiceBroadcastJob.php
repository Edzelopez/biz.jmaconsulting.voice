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
    // before we run jobs, we need to split the jobs
  public static function runJobs_pre($offset = 200, $mode = NULL) {
    $job = new CRM_Mailing_BAO_MailingJob();

    $config       = CRM_Core_Config::singleton();
    $jobTable     = CRM_Mailing_DAO_MailingJob::getTableName();
    $mailingTable = CRM_Mailing_DAO_Mailing::getTableName();

    $currentTime = date('YmdHis');
    $mailingACL = CRM_Mailing_BAO_Mailing::mailingACL('m');


    $workflowClause = CRM_Mailing_BAO_MailingJob::workflowClause();

    $domainID = CRM_Core_Config::domainID();

    $modeClause = 'AND m.sms_provider_id IS NULL';
    if ($mode == 'sms') {
      $modeClause = 'AND m.sms_provider_id IS NOT NULL';
    }

    // Select all the mailing jobs that are created from
    // when the mailing is submitted or scheduled.
    $query = "
    SELECT   j.*
      FROM   $jobTable     j,
         $mailingTable m
     WHERE   m.id = j.mailing_id AND m.domain_id = {$domainID}
                 $workflowClause
                 $modeClause
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
      $lockName = "civimail.job.{$job->id}";

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
} 