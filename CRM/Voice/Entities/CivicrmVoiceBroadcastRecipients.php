<?php

namespace CRM\Voice\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBroadcastRecipients
 *
 * @ORM\Table(name="civicrm_voice_broadcast_recipients", indexes={@ORM\Index(name="voice_id", columns={"voice_id", "phone_id"})})
 * @ORM\Entity
 */
class CivicrmVoiceBroadcastRecipients
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
     * @param int $phoneId
     */
    public function setPhoneId($phoneId)
    {
        $this->phoneId = $phoneId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhoneId()
    {
        return $this->phoneId;
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

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
