<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_structures")
 */
class UnicatStructure
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $title_form;

    /**
     * single | multi
     *
     * @ORM\Column(type="string", length=16)
     */
    protected $entries;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_required;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var UnicatRepository
     *
     * @ORM\ManyToOne(targetEntity="UnicatRepository", inversedBy="structures")
     */
    protected $repository;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
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
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param mixed $is_required
     * @return $this
     */
    public function setIsRequired($is_required)
    {
        $this->is_required = $is_required;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRequired()
    {
        return $this->is_required;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param \SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository $repository
     * @return $this
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return \SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title_form
     * @return $this
     */
    public function setTitleForm($title_form)
    {
        $this->title_form = $title_form;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitleForm()
    {
        return $this->title_form;
    }

    /**
     * @param mixed $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
