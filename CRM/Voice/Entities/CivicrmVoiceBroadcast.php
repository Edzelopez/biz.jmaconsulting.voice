<?php

namespace CRM\Voice\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBroadcast
 *
 * @ORM\Table(name="civicrm_voice_broadcast", indexes={@ORM\Index(name="contact_id", columns={"contact_id"})})
 * @ORM\Entity
 */
class CivicrmVoiceBroadcast
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="domain_id", type="integer", nullable=false)
     */
    private $domainId;

    /**
     * @var integer
     *
     * @ORM\Column(name="campaign_id", type="integer", nullable=false)
     */
    private $campaignId;

    /**
     * @var integer
     *
     * @ORM\Column(name="contact_id", type="integer", nullable=false)
     */
    private $contactId;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone_id", type="integer", nullable=false)
     */
    private $phoneId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_primary", type="boolean", nullable=false)
     */
    private $isPrimary;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone_location", type="integer", nullable=false)
     */
    private $phoneLocation;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone_type", type="integer", nullable=false)
     */
    private $phoneType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_track_call_disposition", type="boolean", nullable=false)
     */
    private $isTrackCallDisposition;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_track_call_duration", type="boolean", nullable=false)
     */
    private $isTrackCallDuration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_track_call_cost", type="boolean", nullable=false)
     */
    private $isTrackCallCost;

    /**
     * @var string
     *
     * @ORM\Column(name="voice_message_file", type="string", length=200, nullable=false)
     */
    private $voiceMessageFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


    /**
     * @param int $campaignId
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @param int $contactId
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;
        return $this;
    }

    /**
     * @return int
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param int $domainId
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $isPrimary
     */
    public function setIsPrimary($isPrimary)
    {
        $this->isPrimary = $isPrimary;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * @param boolean $isTrackCallCost
     */
    public function setIsTrackCallCost($isTrackCallCost)
    {
        $this->isTrackCallCost = $isTrackCallCost;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsTrackCallCost()
    {
        return $this->isTrackCallCost;
    }

    /**
     * @param boolean $isTrackCallDisposition
     */
    public function setIsTrackCallDisposition($isTrackCallDisposition)
    {
        $this->isTrackCallDisposition = $isTrackCallDisposition;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsTrackCallDisposition()
    {
        return $this->isTrackCallDisposition;
    }

    /**
     * @param boolean $isTrackCallDuration
     */
    public function setIsTrackCallDuration($isTrackCallDuration)
    {
        $this->isTrackCallDuration = $isTrackCallDuration;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsTrackCallDuration()
    {
        return $this->isTrackCallDuration;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $phoneId
     */
    public function setPhoneId($phoneId)
    {
        $this->phoneId = $phoneId;
    }

    /**
     * @return int
     */
    public function getPhoneId()
    {
        return $this->phoneId;
    }

    /**
     * @param int $phoneLocation
     */
    public function setPhoneLocation($phoneLocation)
    {
        $this->phoneLocation = $phoneLocation;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhoneLocation()
    {
        return $this->phoneLocation;
    }

    /**
     * @param int $phoneType
     */
    public function setPhoneType($phoneType)
    {
        $this->phoneType = $phoneType;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhoneType()
    {
        return $this->phoneType;
    }

    /**
     * @param string $voiceMessageFile
     */
    public function setVoiceMessageFile($voiceMessageFile)
    {
        $this->voiceMessageFile = $voiceMessageFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getVoiceMessageFile()
    {
        return $this->voiceMessageFile;
    }


}
