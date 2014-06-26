<?php

/**
 * Job.ProcessVoicebroadcast API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_job_processvoicebroadcast_spec(&$spec) {
  $spec['magicword']['api.required'] = 1;
}

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
  if (array_key_exists('magicword', $params) && $params['magicword'] == 'sesame') {
    \CRM\VoiceBroadcast\BAO\CRM_VoiceBroadcast_BAO_VoiceBroadcast::processQueue();
  } else {
    throw new API_Exception(/*errorMessage*/ 'Everyone knows that the magicword is "sesame"', /*errorCode*/ 1234);
  }
}

