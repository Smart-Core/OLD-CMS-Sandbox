<?php

namespace Demo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * -ORM\Entity()
 * @ORM\Table(name="test1")
 */
class Test1
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }
}
