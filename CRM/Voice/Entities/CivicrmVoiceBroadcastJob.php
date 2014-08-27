<?php

namespace CRM\Voice\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBroadcastJob
 *
 * @ORM\Table(name="civicrm_voice_braodcast_job", indexes={@ORM\Index(name="FK_civicrm_mailing_job_mailing_id", columns={"voice_id"}), @ORM\Index(name="FK_civicrm_mailing_job_parent_id", columns={"parent_id"})})
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

    /**
     * @param boolean $isTest
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $jobLimit
     */
    public function setJobLimit($jobLimit)
    {
        $this->jobLimit = $jobLimit;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobLimit()
    {
        return $this->jobLimit;
    }

    /**
     * @param int $jobOffset
     */
    public function setJobOffset($jobOffset)
    {
        $this->jobOffset = $jobOffset;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobOffset()
    {
        return $this->jobOffset;
    }

    /**
     * @param string $jobType
     */
    public function setJobType($jobType)
    {
        $this->jobType = $jobType;
        return $this;
    }

    /**
     * @return string
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param \DateTime $scheduledDate
     */
    public function setScheduledDate($scheduledDate)
    {
        $this->scheduledDate = $scheduledDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getScheduledDate()
    {
        return $this->scheduledDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $voiceId
     */
    public function setVoiceId($voiceId)
    {
        $this->voiceId = $voiceId;
        return $this;
    }

    /**
     * @return int
     */
    public function getVoiceId()
    {
        return $this->voiceId;
    }
}
