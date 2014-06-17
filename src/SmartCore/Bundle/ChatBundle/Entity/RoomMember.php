<?php

namespace SmartCore\Bundle\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="chat_rooms_members",
 *      indexes={
 *          @ORM\Index(name="status", columns={"status"}),
 *          @ORM\Index(name="created_at", columns={"created_at"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="user_in_room", columns={"user_id", "room_id"}),
 *      }
 * )
 * @UniqueEntity(fields={"user_id", "room"}, message="Пользователь уже добавлен в беседу.")
 */
class RoomMember
{
    const STATUS_ACTIVE = 0;
    const STATUS_BAN    = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $status;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @var Room
     *
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="members")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $room;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * Constructor.
     */
    public function __construct()
    {
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
