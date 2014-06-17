<?php

namespace SmartCore\Bundle\ChatBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="chat_rooms",
 *      indexes={
 *          @ORM\Index(name="is_dialog", columns={"is_dialog"}),
 *          @ORM\Index(name="creator_user_id", columns={"creator_user_id"}),
 *          @ORM\Index(name="created_at", columns={"created_at"}),
 *      }
 * )
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_dialog;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $creator_user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RoomMember", mappedBy="room", cascade={"persist", "remove"})
     */
    protected $members;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="room", cascade={"persist", "remove"})
     */
    protected $messages;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->is_dialog    = true;
        $this->created_at   = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
