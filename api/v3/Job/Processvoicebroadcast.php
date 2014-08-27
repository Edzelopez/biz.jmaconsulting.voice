<?php

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Job processvoicebroadcast API
 *
 *  It basically call VoiceBroadcast::processQueue
 * for further process in while cron job run
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_job_processvoicebroadcast($params = array()) {
  CRM_VoiceBroadcast_BAO_VoiceBroadcast::processQueue();
}

