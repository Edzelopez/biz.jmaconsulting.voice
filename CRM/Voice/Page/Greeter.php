<?php

require_once 'CRM/Core/Page.php';
require_once __DIR__. '/../../../bootstrap.php';



class CRM_Voice_Page_Greeter extends CRM_Core_Page
{
  function run()
  {
      global $entityManager;

      $recipientEntity = new \CRM\Voice\Entities\CivicrmVoiceBroadcastRecipients();
      $recipientEntity->setContactId(1);
      $recipientEntity->setPhoneId(1);
      $recipientEntity->setVoiceId(1);

      $entityManager->persist($recipientEntity);
      $entityManager->flush();
      echo 'done'; exit;
  }
}
