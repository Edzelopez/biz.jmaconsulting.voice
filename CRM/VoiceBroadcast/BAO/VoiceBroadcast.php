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

        $file = './people.txt';
        // Open the file to get existing content
        $current = file_get_contents($file);
        // Append a new person to the file
        $current .= "John Smith\n";
        // Write the contents back to the file
        file_put_contents($file, $current);

        return TRUE;
    }
} 