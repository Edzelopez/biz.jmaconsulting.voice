<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/26/14
 * Time: 11:48 AM
 */



namespace CRM\VoiceBroadcast\BAO;


class VoiceBroadcast
{
    static function processQueue($path = NULL)
    {

      $entityManager  = require __DIR__. '/../../../bootstrap.php';



      $recipientEntity = new \CRM\Voice\Entities\CivicrmVoiceBroadcastRecipients();
      $recipientEntity->setContactId(2);
      $recipientEntity->setPhoneId(2);
      $recipientEntity->setVoiceId(2);



      $entityManager->persist($recipientEntity);
      $entityManager->flush();
      echo 'done'; exit;



    }
} 