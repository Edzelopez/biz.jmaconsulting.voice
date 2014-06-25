<?php

namespace CRM\Voice\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBroadcastSpool
 *
 * @ORM\Table(name="civicrm_voice_broadcast_spool", indexes={@ORM\Index(name="voice_id", columns={"job_id"})})
 * @ORM\Entity
 */
class CivicrmVoiceBroadcastSpool
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="job_id", type="integer", nullable=false)
     */
    private $jobId;

    /**
     * @var string
     *
     * @ORM\Column(name="recipient_number", type="string", length=20, nullable=false)
     */
    private $recipientNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_at", type="datetime", nullable=false)
     */
    private $addedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="removed_at", type="datetime", nullable=false)
     */
    private $removedAt;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param string $recipientNumber
     */
    public function setRecipientNumber($recipientNumber)
    {
        $this->recipientNumber = $recipientNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientNumber()
    {
        return $this->recipientNumber;
    }

    /**
     * @param \DateTime $removedAt
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * @param \DateTime $addedAt
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }
}
