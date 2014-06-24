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


}
