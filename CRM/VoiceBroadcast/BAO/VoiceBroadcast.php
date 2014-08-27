<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/26/14
 * Time: 11:48 AM
 */




require_once 'CRM/Core/Config.php';

class CRM_VoiceBroadcast_BAO_VoiceBroadcast
{
    static function processQueue($mode = NULL)
    {

        //Doctrine Entity
        $entityManager  = require __DIR__. '/../../../bootstrap.php';
        $config         = &CRM_Core_Config::singleton();


        //Checking that voice broadcast's setting  is configure or not
        if ($mode == NULL && CRM_Core_BAO_MailSettings::defaultDomain() == "EXAMPLE.ORG") {
          CRM_Core_Error::fatal(ts('The <a href="%1">default mailbox</a> has not been configured. You will find <a href="%2">more info in the online user and administrator guide</a>', array(1 => CRM_Utils_System::url('civicrm/admin/mailSettings', 'reset=1'), 2 => "http://book.civicrm.org/user/advanced-configuration/email-system-configuration/")));
        }

        // check if we are enforcing number of parallel cron jobs
        // CRM-8460
        $gotCronLock = FALSE;

        if (property_exists($config, 'mailerJobsMax')
            && $config->mailerJobsMax
            && $config->mailerJobsMax > 1) {

            $lockArray = range(1, $config->mailerJobsMax);
            shuffle($lockArray);

            // check if we are using global locks
            $serverWideLock = CRM_Core_BAO_Setting::getItem(CRM_Core_BAO_Setting::MAILING_PREFERENCES_NAME,
                                                            'civimail_server_wide_lock'
                                                           );

          foreach ($lockArray as $lockID)
          {
            $cronLock = new CRM_Core_Lock("civimail.cronjob.{$lockID}", NULL, $serverWideLock);

            if ($cronLock->isAcquired()) {
              $gotCronLock = TRUE;
              break;
            }
          }

          // exit here since we have enuf cronjobs running
          if (!$gotCronLock) {
            CRM_Core_Error::debug_log_message('Returning early, since max number of cronjobs running');
            return TRUE;
          }
        }

        //Load bootstrap to call hooks

        // Split up the parent jobs into multiple child jobs
        $mailerJobSize = (property_exists($config, 'mailerJobSize')) ? $config->mailerJobSize : NULL;

        //Pre Job: Split schedule and enqueue the task
        CRM_VoiceBroadcast_BAO_VoiceBroadcastJob::runJobs_pre($mailerJobSize, $mode, $entityManager);

        //Process the job
        CRM_VoiceBroadcast_BAO_VoiceBroadcastJob::runJobs(NULL, $mode, $entityManager);

        //Status update after the job
        CRM_VoiceBroadcast_BAO_VoiceBroadcastJob::runJobs_post($mode, $entityManager);

        // lets release the global cron lock if we do have one
        if ($gotCronLock) {
          $cronLock->release();
        }

        return TRUE;
    }

 /**
   * Construct a new voicebroadcast object, along with job and voicebroadcast_group
   * objects, from the form values of the create mailing wizard.
   *
   * @params array $params        Form values
   *
   * @param $params
   * @param array $ids
   *
   * @return object $mailing      The new mailing object
   * @access public
   * @static
   */
  public static function create(&$params, $ids = array()) {

    // CRM-12430
    // Do the below only for an insert
    // for an update, we should not set the defaults
    if (!isset($ids['id']) && !isset($ids['mailing_id'])) {
      // Retrieve domain email and name for default sender
      $domain = civicrm_api(
        'Domain',
        'getsingle',
        array(
          'version' => 3,
          'current_domain' => 1,
          'sequential' => 1,
        )
      );
      if (isset($domain['from_email'])) {
        $domain_email = $domain['from_email'];
        $domain_name  = $domain['from_name'];
      }
      else {
        $domain_email = 'info@EXAMPLE.ORG';
        $domain_name  = 'EXAMPLE.ORG';
      }
      if (!isset($params['created_id'])) {
        $session =& CRM_Core_Session::singleton();
        $params['created_id'] = $session->get('userID');
      }
      $defaults = array(
        // load the default config settings for each
        // eg reply_id, unsubscribe_id need to use
        // correct template IDs here
        'override_verp'   => TRUE,
        'forward_replies' => FALSE,
        'open_tracking'   => TRUE,
        'url_tracking'    => TRUE,
        'visibility'      => 'Public Pages',
        'replyto_email'   => $domain_email,
        'header_id'       => CRM_Mailing_PseudoConstant::defaultComponent('header_id', ''),
        'footer_id'       => CRM_Mailing_PseudoConstant::defaultComponent('footer_id', ''),
        'from_email'      => $domain_email,
        'from_name'       => $domain_name,
        'msg_template_id' => NULL,
        'created_id'      => $params['created_id'],
        'approver_id'     => NULL,
        'auto_responder'  => 0,
        'created_date'    => date('YmdHis'),
        'scheduled_date'  => NULL,
        'approval_date'   => NULL,
      );

      // Get the default from email address, if not provided.
      if (empty($defaults['from_email'])) {
        $defaultAddress = CRM_Core_OptionGroup::values('from_email_address', NULL, NULL, NULL, ' AND is_default = 1');
        foreach ($defaultAddress as $id => $value) {
          if (preg_match('/"(.*)" <(.*)>/', $value, $match)) {
            $defaults['from_email'] = $match[2];
            $defaults['from_name'] = $match[1];
          }
        }
      }

      $params = array_merge($defaults, $params);
    }

    /**
     * Could check and warn for the following cases:
     *
     * - groups OR mailings should be populated.
     * - body html OR body text should be populated.
     */

    $transaction = new CRM_Core_Transaction();

    $mailing = self::add($params, $ids);

    if (is_a($mailing, 'CRM_Core_Error')) {
      $transaction->rollback();
      return $mailing;
    }
    // update mailings with hash values
    CRM_Contact_BAO_Contact_Utils::generateChecksum($mailing->id, NULL, NULL, NULL, 'mailing', 16);

    $groupTableName = CRM_Contact_BAO_Group::getTableName();
    $mailingTableName = 'civicrm_voice_broadcast';

    /* Create the mailing group record */
    $mg = new CRM_Mailing_DAO_MailingGroup();
    foreach (array('groups', 'mailings') as $entity) {
      foreach (array('include', 'exclude', 'base') as $type) {
        if (isset($params[$entity]) && !empty($params[$entity][$type]) &&
          is_array($params[$entity][$type])) {
          foreach ($params[$entity][$type] as $entityId) {
            $mg->reset();
            $mg->mailing_id   = $mailing->id;
            $mg->entity_table = ($entity == 'groups') ? $groupTableName : $mailingTableName;
            $mg->entity_id    = $entityId;
            $mg->group_type   = $type;
            $mg->save();
          }
        }
      }
    }

    if (!empty($params['search_id']) && !empty($params['group_id'])) {
      $mg->reset();
      $mg->mailing_id   = $mailing->id;
      $mg->entity_table = $groupTableName;
      $mg->entity_id    = $params['group_id'];
      $mg->search_id    = $params['search_id'];
      $mg->search_args  = $params['search_args'];
      $mg->group_type   = 'Include';
      $mg->save();
    }

    // check and attach and files as needed
    CRM_Core_BAO_File::processAttachment($params, 'civicrm_mailing', $mailing->id);

    $transaction->commit();

    /**
     * create parent job if not yet created
     * condition on the existence of a scheduled date
     */
    if (!empty($params['scheduled_date']) && $params['scheduled_date'] != 'null') {
      $job = new CRM_Mailing_BAO_MailingJob();
      $job->mailing_id = $mailing->id;
      $job->status = 'Scheduled';
      $job->is_test = 0;

      if ( !$job->find(TRUE) ) {
        $job->scheduled_date = $params['scheduled_date'];
        $job->save();
      }

      // Populate the recipients.
      $mailing->getRecipients($job->id, $mailing->id, NULL, NULL, TRUE, FALSE);
    }

    return $mailing;
  }
}