<?php

require_once 'CRM/Core/Page.php';

class CRM_Voice_Page_Voice extends CRM_Core_Page {
  function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('Voice'));
    CRM_Core_Resources::singleton()->addScriptFile('biz.jmaconsulting.voice', 'bootstrap.js');

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));



    parent::run();
  }
}
