<?php

namespace SmartCore\Bundle\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *      name="chat_messages",
 *      indexes={
 *          @ORM\Index(name="date", columns={"date"}),
 *          @ORM\Index(name="user_id", columns={"user_id"}),
 *      }
 * )
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var Room
     *
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="messages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $room;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
}
