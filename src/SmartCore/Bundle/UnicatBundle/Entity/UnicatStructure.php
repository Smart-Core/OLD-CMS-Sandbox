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
     * @ORM\Column(type="smallint", nullable=true)
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
        $this->position   = 0;
        $this->user_id    = 0;
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

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param string $entries
     * @return $this
     */
    public function setEntries($entries)
    {
        $this->entries = $entries;

        return $this;
    }

    /**
     * @param bool $is_required
     * @return $this
     */
    public function setIsRequired($is_required)
    {
        $this->is_required = $is_required;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->is_required;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param UnicatRepository $repository
     * @return $this
     */
    public function setRepository(UnicatRepository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return UnicatRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
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
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        if (is_object($user_id) and method_exists($user_id, 'getId')) {
            $user_id = $user_id->getId();
        }

        if (null === $user_id) {
            $user_id = 0;
        }

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
