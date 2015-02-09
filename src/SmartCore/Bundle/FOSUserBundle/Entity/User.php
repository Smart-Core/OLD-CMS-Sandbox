<?php

namespace SmartCore\Bundle\FOSUserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    use CreatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $firstname = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $lastname = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->created_at = new \DateTime();
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
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName().' '.$this->getLastname();
    }
}
