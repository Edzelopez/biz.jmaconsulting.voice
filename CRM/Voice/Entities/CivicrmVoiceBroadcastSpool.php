<?php



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


}
