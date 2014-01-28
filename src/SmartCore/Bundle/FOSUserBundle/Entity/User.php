<?php

namespace SmartCore\Bundle\FOSUserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $firstname = '';

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $lastname = '';

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255)
     * @var string
     */
    protected $facebookId = '';

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->created = new \DateTime();
    }

    public function serialize()
    {
        return serialize([
            $this->facebookId,
            parent::serialize()
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->facebookId,
            $parentData
        ) = unserialize($data);
        parent::unserialize($parentData);
    }

    /**
     * @return \Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastname();
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
        $this->salt = '';
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }

        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }

        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }

        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
    }
}
