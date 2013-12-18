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
        
    public function __construct()
    {
        $this->user_id = 0;
        $this->time = new \DateTime();
    }
    
    public function getId()
    {
        return true;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getTime()
    {
        return $this->time;
    }
    
    public function setTime($time)
    {
        $this->time = $time;
    }
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    
}