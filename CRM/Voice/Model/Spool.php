<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/23/14
 * Time: 11:46 AM
 */

namespace CRM\Voice\Model;

require_once CRM_Queue_Service;

class Spool
{
    const QUEUE_NAME = 'voice-broadcast-queue';

    public $queue;

    public function __construct($dao = null)
    {
       $this->queue = CRM_Queue_Service::singleton()->create(array('type'  => 'Memory',
                                                                   'name'  => self::QUEUE_NAME,
                                                                   'reset' => TRUE,
                                                                    )
                                                            );
    }

    public function spoolJobs()
    {

    }

    public function unSpoolJobs()
    {

    }
} 