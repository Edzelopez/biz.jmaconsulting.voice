<?php



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


}
