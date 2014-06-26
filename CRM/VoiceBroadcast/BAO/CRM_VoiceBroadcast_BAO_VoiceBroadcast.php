<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/26/14
 * Time: 11:48 AM
 */

namespace CRM\VoiceBroadcast\BAO;


class CRM_VoiceBroadcast_BAO_VoiceBroadcast
{
    static function processQueue($mode = NULL)
    {
        $text = 'voice is broadcasted';
        $logFileName = empty($path)? "/system_log.text" : $path . "/system_log.text";
        $logger = fopen($logFileName, "a") or die("Could not open log file.");

        fwrite($logger, date("d-m-Y, H:i")." - $text\n") or die("Could not write file!");
        fclose($logger);

        return TRUE;
    }
} 