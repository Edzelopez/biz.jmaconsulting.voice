<?php

/**
 * Job.ProcessVoicebroadcast API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_job_processvoicebroadcast($params) {
    \CRM\VoiceBroadcast\BAO\CRM_VoiceBroadcast_BAO_VoiceBroadcast::processQueue();
}

