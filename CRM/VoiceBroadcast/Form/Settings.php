<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */

/**
 * This file is used to build the form configuring mailing details
 */
class CRM_VoiceBroadcast_Form_Settings extends CRM_Core_Form {

  /**
   * Function to set variables up before form is built
   *
   * @return void
   * @access public
   */
  public function preProcess() {
    //when user come from search context.
    $ssID = $this->get('ssID');
    $this->assign('ssid',$ssID);
    //$this->_searchBasedMailing = CRM_Contact_Form_Search::isSearchContext($this->get('context'));
//    //if(CRM_Contact_Form_Search::isSearchContext($this->get('context')) && !$ssID){
//    $params = array();
//    $result = CRM_Core_BAO_PrevNextCache::getSelectedContacts();
//    $this->assign("value", $result);
//    }
  }

  /**
   * This function sets the default values for the form.
   * the default values are retrieved from the database
   *
   * @access public
   *
   * @return None
   */
  function setDefaultValues()
  {
    $entityManager = require __DIR__. '/../../../bootstrap.php';
    $mailingID = CRM_Utils_Request::retrieve('mid', 'Integer', $this, FALSE, NULL);
    $count = $this->get('count');
    $this->assign('count', $count);
    $defaults = array();



    if ($mailingID) {
      $voiceBroadCast = $entityManager->getRepository('CRM\Voice\Entities\CivicrmVoiceBroadcast')->findOneBy(array('voice_id' => $mailingID ));

      if(!empty($voiceBroadCastJob)) {
        $defaults['is_track_call_duration']    = $voiceBroadCast->getIsTrackCallDuration();
        $defaults['is_track_call_disposition'] = $voiceBroadCast->getIsTrackCallDisposition();
        $defaults['is_track_call_cost']        = $voiceBroadCast->getIsTrackCallCost();
      }
    }

    return $defaults;
  }

  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {

    $this->add('checkbox', 'is_track_call_disposition', '');
    $defaults['is_track_call_disposition']  = false;

    $this->add('checkbox', 'is_track_call_duration', '');
    $defaults['is_track_call_duration']     = false;

    $this->add('checkbox', 'is_track_call_cost', '');
    $defaults['is_track_call_cost']         = false;

    $buttons = array(
      array('type' => 'back',
        'name' => ts('<< Previous'),
      ),
      array(
        'type' => 'next',
        'name' => ts('Next >>'),
        'spacing' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;',
        'isDefault' => TRUE,
      ),
      array(
        'type' => 'submit',
        'name' => ts('Save & Continue Later'),
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ),
    );

    $this->addButtons($buttons);

    $this->setDefaults($defaults);
  }

  public function postProcess()
  {
    $entityManager = require __DIR__. '/../../../bootstrap.php';
    $params = $ids = array();

    $session = CRM_Core_Session::singleton();
    $params['created_id'] = $session->get('userID');

    $uploadParamsBoolean = array('is_track_call_disposition', 'is_track_call_duration', 'is_track_call_cost');

    foreach ($uploadParamsBoolean as $key)
    {
      if ($this->controller->exportvalue($this->_name, $key)) {
        $params[$key] = TRUE;
      }
      else {
        $params[$key] = FALSE;
      }
      $this->set($key, $this->controller->exportvalue($this->_name, $key));
    }

    $ids['mailing_id'] = $this->get('mailing_id');

    // update voice broadcast
    if (!empty($ids['mailing_id'])) {
      $voiceBroadCast = $entityManager->getRepository('CRM\Voice\Entities\CivicrmVoiceBroadcast')->findOneBy(array('id' => $ids['mailing_id'] ));

      if(!empty($voiceBroadCast)) {
        $voiceBroadCast->setIsTrackCallDuration($params['is_track_call_duration']);
        $voiceBroadCast->setIsTrackCallDisposition($params['is_track_call_disposition']);
        $voiceBroadCast->setIsTrackCallCost($params['is_track_call_cost']);

        $entityManager->persist($voiceBroadCast);
        $entityManager->flush();
      }
    }
  }

  /**
   * Display Name of the form
   *
   * @access public
   *
   * @return string
   */
  public function getTitle() {
    return ts('Track and Respond');
  }
}
