<?php


namespace CRM\Voice\Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBraodcastGroup
 *
 * @ORM\Table(name="civicrm_voice_braodcast_group", indexes={@ORM\Index(name="voice_id", columns={"voice_id", "entity_id"})})
 * @ORM\Entity
 */
class CivicrmVoiceBraodcastGroup
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
     * @var string
     *
     * @ORM\Column(name="group_type", type="string", nullable=false)
     */
    private $groupType;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_table", type="integer", nullable=false)
     */
    private $entityTable;



    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @param int $entityTable
     */
    public function setEntityTable($entityTable)
    {
        $this->entityTable = $entityTable;
        return $this;
    }

    /**
     * @return int
     */
    public function getEntityTable()
    {
        return $this->entityTable;
    }

    /**
     * @param string $groupType
     */
    public function setGroupType($groupType)
    {
        $this->groupType = $groupType;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupType()
    {
        return $this->groupType;
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
