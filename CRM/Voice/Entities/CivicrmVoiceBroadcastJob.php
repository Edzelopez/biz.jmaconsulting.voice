<?php

namespace CRM\Voice\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBroadcastJob
 *
 * @ORM\Table(name="civicrm_voice_broadcast_job", indexes={@ORM\Index(name="FK_civicrm_mailing_job_mailing_id", columns={"voice_id"}), @ORM\Index(name="FK_civicrm_mailing_job_parent_id", columns={"parent_id"})})
 * @ORM\Entity
 */
class CivicrmVoiceBroadcastJob
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="voice_id", type="integer", nullable=false)
     */
    private $voiceId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scheduled_date", type="datetime", nullable=true)
     */
    private $scheduledDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_test", type="boolean", nullable=true)
     */
    private $isTest = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="job_type", type="string", length=255, nullable=true)
     */
    private $jobType;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="job_offset", type="integer", nullable=true)
     */
    private $jobOffset = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="job_limit", type="integer", nullable=true)
     */
    private $jobLimit = '0';


}
