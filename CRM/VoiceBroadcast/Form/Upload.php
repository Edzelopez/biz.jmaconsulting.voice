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

  function preProcess() {
    $this->_mailingID = $this->get('mailing_id');

    //when user come from search context.
    $ssID = $this->get('ssID');
    $this->assign('ssid',$ssID);
//    $this->_searchBasedMailing = CRM_Contact_Form_Search::isSearchContext($this->get('context'));
//    if(CRM_Contact_Form_Search::isSearchContext($this->get('context')) && !$ssID){
//      $params = array();
//      $result = CRM_Core_BAO_PrevNextCache::getSelectedContacts();
//      $this->assign("value", $result);
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
    $mailingID = CRM_Utils_Request::retrieve('mid', 'Integer', $this, FALSE, NULL);

    //need to differentiate new/reuse mailing, CRM-2873
    $reuseMailing = FALSE;
    if ($mailingID) {
      $reuseMailing = TRUE;
    }
    else {
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

      //we don't want to retrieve template details once it is
      //set in session
//      $templateId = $this->get('template');
//      $this->assign('templateSelected', $templateId ? $templateId : 0);
//      if (isset($defaults['msg_template_id']) && !$templateId) {
//        $defaults['template'] = $defaults['msg_template_id'];
//        $messageTemplate = new CRM_Core_DAO_MessageTemplate();
//        $messageTemplate->id = $defaults['msg_template_id'];
//        $messageTemplate->selectAdd();
//        $messageTemplate->selectAdd('msg_text, msg_html');
//        $messageTemplate->find(TRUE);
//
//        $defaults['text_message'] = $messageTemplate->msg_text;
//        $htmlMessage = $messageTemplate->msg_html;
//      }
//
//      if (isset($defaults['body_text'])) {
//        $defaults['text_message'] = $defaults['body_text'];
//        $this->set('textFile', $defaults['body_text']);
//        $this->set('skipTextFile', TRUE);
//      }
//
//      if (isset($defaults['body_html'])) {
//        $htmlMessage = $defaults['body_html'];
//        $this->set('htmlFile', $defaults['body_html']);
//        $this->set('skipHtmlFile', TRUE);
//      }
//
//      //set default from email address.
//      if (CRM_Utils_Array::value('from_name', $defaults) && CRM_Utils_Array::value('from_email', $defaults)) {
//        $defaults['from_email_address'] = array_search('"' . $defaults['from_name'] . '" <' . $defaults['from_email'] . '>',
//          CRM_Core_OptionGroup::values('from_email_address')
//        );
//      }
//      else {
//        //get the default from email address.
//        $defaultAddress = CRM_Core_OptionGroup::values('from_email_address', NULL, NULL, NULL, ' AND is_default = 1');
//        foreach ($defaultAddress as $id => $value) {
//          $defaults['from_email_address'] = $id;
//        }
//      }
//
//      if (CRM_Utils_Array::value('replyto_email', $defaults)) {
//        $replyToEmail = CRM_Core_OptionGroup::values('from_email_address');
//        foreach ($replyToEmail as $value) {
//          if (strstr($value, $defaults['replyto_email'])) {
//            $replyToEmailAddress = $value;
//            break;
//          }
//        }
//        $replyToEmailAddress = explode('<', $replyToEmailAddress);
//        if (count($replyToEmailAddress) > 1) {
//          $replyToEmailAddress = $replyToEmailAddress[0] . '<' . $replyToEmailAddress[1];
//        }
//        $defaults['reply_to_address'] = array_search($replyToEmailAddress, $replyToEmail);
//      }
//    }

//    //fix for CRM-2873
//    if (!$reuseMailing) {
//      $textFilePath = $this->get('textFilePath');
//      if ($textFilePath &&
//        file_exists($textFilePath)
//      ) {
//        $defaults['text_message'] = file_get_contents($textFilePath);
//        if (strlen($defaults['text_message']) > 0) {
//          $this->set('skipTextFile', TRUE);
//        }
//      }
//
//      $htmlFilePath = $this->get('htmlFilePath');
//      if ($htmlFilePath &&
//        file_exists($htmlFilePath)
//      ) {
//        $defaults['html_message'] = file_get_contents($htmlFilePath);
//        if (strlen($defaults['html_message']) > 0) {
//          $htmlMessage = $defaults['html_message'];
//          $this->set('skipHtmlFile', TRUE);
//        }
//      }
//    }
//
//    if ($this->get('html_message')) {
//      $htmlMessage = $this->get('html_message');
//    }
//
//    $htmlMessage = str_replace(array("\n", "\r"), ' ', $htmlMessage);
//    $htmlMessage = str_replace("'", "\'", $htmlMessage);
//    $this->assign('message_html', $htmlMessage);
//
//    $defaults['upload_type'] = 1;
//    if (isset($defaults['body_html'])) {
//      $defaults['html_message'] = $defaults['body_html'];
//    }
//
//    //CRM-4678 setdefault to default component when composing new mailing.
//    if (!$reuseMailing) {
//      $componentFields = array(
//        'header_id' => 'Header',
//        'footer_id' => 'Footer',
//      );
//      foreach ($componentFields as $componentVar => $componentType) {
//        $defaults[$componentVar] = CRM_Mailing_PseudoConstant::defaultComponent($componentType, '');
//      }
//    }

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

    $this->addElement('file', 'textFile', ts('Upload Voice Message'), 'size=30 maxlength=60');
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

  public function postProcess() {
    $params       = $ids = array();
    $uploadParams = array('caller');
    $fileType     = array('textFile');

    $formValues = $this->controller->exportValues($this->_name);



    $session = CRM_Core_Session::singleton();
    $params['contact_id'] = $session->get('userID');



    CRM_Core_BAO_File::formatAttachment($formValues,
                                          $params,
                                          'civicrm_mailing',
                                          $this->_mailingID
                                        );
    $ids['mailing_id'] = $this->_mailingID;


    /* Build the mailing object */

    CRM_Mailing_BAO_Mailing::create($params, $ids);

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

