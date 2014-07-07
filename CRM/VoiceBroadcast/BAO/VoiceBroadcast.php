<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/26/14
 * Time: 11:48 AM
 */



namespace CRM\VoiceBroadcast\BAO;

require_once 'CRM/Core/Config.php';

class VoiceBroadcast
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

        // load bootstrap to call hooks

        // Split up the parent jobs into multiple child jobs
        $mailerJobSize = (property_exists($config, 'mailerJobSize')) ? $config->mailerJobSize : NULL;
        VoiceBroadcastJob::runJobs_pre($mailerJobSize, $mode);
        VoiceBroadcastJob::runJobs(NULL, $mode);
        VoiceBroadcastJob::runJobs_post($mode);

        // lets release the global cron lock if we do have one
        if ($gotCronLock) {
          $cronLock->release();
        }

        return TRUE;
    }
}