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
class CRM_VoiceBroadcast_Form_Upload extends CRM_Core_Form
{
  public $_mailingID;


  public function preProcess()
  {
    $this->_mailingID = $this->get('mailing_id');

    //when user come from search context.
    $ssID = $this->get('ssID');
    $this->assign('ssid',$ssID);
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
    $mailingID = CRM_Utils_Request::retrieve('mid', 'Integer', $this, FALSE, NULL);

    //need to differentiate new/reuse mailing, CRM-2873
    $reuseMailing = FALSE;

    if ($mailingID) {
      $reuseMailing = TRUE;
    } else {
      $mailingID = $this->_mailingID;
    }

    $count = $this->get('count');
    $this->assign('count', $count);

    $this->set('skipTextFile', FALSE);
    $this->set('skipHtmlFile', FALSE);

    $defaults = array();

    $htmlMessage = NULL;
    if ($mailingID) {
      $dao = new CRM_Mailing_DAO_Mailing();
      $dao->id = $mailingID;
      $dao->find(TRUE);
      $dao->storeValues($dao, $defaults);
    }

    return $defaults;
  }

  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm()
  {
    $session = CRM_Core_Session::singleton();
    $config  = CRM_Core_Config::singleton();
    $options = array();
    $tempVar = FALSE;

//    // this seems so hacky, not sure what we are doing here and why. Need to investigate and fix
//    $session->getVars($options,
//      "CRM_Mailing_Controller_Send_{$this->controller->_key}"
//    );

    $this->add('text',
                'caller',
                ts('Caller'),
                'caller',
                TRUE
    );

    $this->addElement('file', 'textFile', ts('Upload Voice Message'));
    $this->addUploadElement('textFile');
    $this->addFormRule(array('CRM_VoiceBroadcast_Form_Upload', 'formRule'), $this);

    $buttons = array(
      array('type' => 'back',
        'name' => ts('<< Previous'),
      ),
      array(
        'type' => 'upload',
        'name' => ts('Next >>'),
        'spacing' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;',
        'isDefault' => TRUE,
      ),
      array(
        'type' => 'upload',
        'name' => ts('Save & Continue Later'),
        'subName' => 'save',
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ),
    );
    $this->addButtons($buttons);
  }

  public function postProcess()
  {

    $entityManager = require __DIR__. '/../../../bootstrap.php';

    $params       = $ids = array();
    $uploadParams = array('caller');
    $fileType     = array('textFile');

    $formValues = $this->controller->exportValues($this->_name);



    $session = CRM_Core_Session::singleton();
    $params['contact_id'] = $session->get('userID');


    $ids['mailing_id'] = $this->_mailingID;



    // Remove backslashes and forward slashes from new filename
    $voiceFileName = strtr($_FILES['textFile']['name'],'/\\','');



    // Remove ".." from new filename
    $voiceFileName = str_replace('..', '', $voiceFileName);


    $config = CRM_Core_Config::singleton();


    //File Uploading
    move_uploaded_file($_FILES['textFile']['tmp_name'], $config->uploadDir . $voiceFileName);



    $voiceEntity = $entityManager->getRepository('CRM\Voice\Entities\CivicrmVoiceBroadcast')->findOneBy(array('id' => $ids['mailing_id']));
    $voiceEntity->setVoiceMessageFile($config->uploadDir .  $voiceFileName);
    $entityManager->persist($voiceEntity);
    $entityManager->flush();
  }

  /**
   * Function for validation
   *
   * @param array $params (ref.) an assoc array of name/value pairs
   *
   * @return mixed true or array of errors
   * @access public
   * @static
   */
  static function formRule($params, $files, $self)
  {

    $errors = array();
    $template = CRM_Core_Smarty::singleton();



    $skipTextFile = $self->get('skipTextFile');

    if (!$params) {
      if ((!isset($files['textFile']) || !file_exists($files['textFile']['tmp_name']))
      ) {
        if (!($skipTextFile)) {
          $errors['textFile'] = ts('Please provide Voice message');
        }
      }
    }

    return empty($errors) ? TRUE : $errors;
  }

  /**
   * Display Name of the form
   *
   * @access public
   *
   * @return string
   */
  public function getTitle() {
    return ts('Voice Message Content');
  }
}

