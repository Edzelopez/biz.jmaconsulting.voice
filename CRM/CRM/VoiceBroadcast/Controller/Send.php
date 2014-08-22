<?php


/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */
class CRM_VoiceBroadcast_Controller_Send extends CRM_Core_Controller
{

  /**
   * class constructor
   */
  function __construct($title = NULL, $action = CRM_Core_Action::NONE, $modal = TRUE) {

    parent::__construct($title, $modal, NULL, FALSE, TRUE);
    $entityManager  = require_once __DIR__. '/../../../bootstrap.php';

    $mailingID = CRM_Utils_Request::retrieve('mid', 'String', $this, FALSE, NULL);

    // also get the text and html file
    $txtFile = CRM_Utils_Request::retrieve('txtFile', 'String',
      CRM_Core_DAO::$_nullObject, FALSE, NULL
    );
    $htmlFile = CRM_Utils_Request::retrieve('htmlFile', 'String',
      CRM_Core_DAO::$_nullObject, FALSE, NULL
    );

    $config = CRM_Core_Config::singleton();
    if ($txtFile &&
      file_exists($config->uploadDir . $txtFile)
    ) {
      $this->set('textFilePath', $config->uploadDir . $txtFile);
    }

    if ($htmlFile &&
      file_exists($config->uploadDir . $htmlFile)
    ) {
      $this->set('htmlFilePath', $config->uploadDir . $htmlFile);
    }


    $this->_stateMachine = new CRM_VoiceBroadcast_StateMachine_Send($this, $action, $mailingID);

    // create and instantiate the pages
    $this->addPages($this->_stateMachine, $action);

    // add all the actions
    $uploadNames = array_merge(array('textFile', 'htmlFile'),
      CRM_Core_BAO_File::uploadNames()
    );

    $config = CRM_Core_Config::singleton();
    $this->addActions($config->uploadDir,
      $uploadNames
    );
  }
}

