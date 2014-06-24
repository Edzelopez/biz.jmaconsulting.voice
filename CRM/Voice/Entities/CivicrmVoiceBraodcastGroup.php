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


}
