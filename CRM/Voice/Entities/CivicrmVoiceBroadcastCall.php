<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CivicrmVoiceBroadcastCall
 *
 * @ORM\Table(name="civicrm_voice_broadcast_call", indexes={@ORM\Index(name="voice_id", columns={"job_id", "phone_id"}), @ORM\Index(name="contact_id", columns={"contact_id"})})
 * @ORM\Entity
 */
class CivicrmVoiceBroadcastCall
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
     * @ORM\Column(name="job_id", type="integer", nullable=false)
     */
    private $jobId;

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
     * @var string
     *
     * @ORM\Column(name="disposition", type="string", length=10, nullable=true)
     */
    private $disposition;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=10, nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="string", length=10, nullable=true)
     */
    private $cost;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


}
