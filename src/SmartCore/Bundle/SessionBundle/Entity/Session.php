<?php

namespace SmartCore\Bundle\SessionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="session",
 *      indexes={
 *          @ORM\Index(name="user_id", columns={"user_id"}),
 *          @ORM\Index(name="time", columns={"time"}),
 *      }
 * )
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", nullable=false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $user_id;
    
    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $data;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user_id = 0;
        $this->time = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return $this
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
