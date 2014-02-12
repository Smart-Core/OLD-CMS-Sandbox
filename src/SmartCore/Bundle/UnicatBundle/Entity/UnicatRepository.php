<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_repositories",
 *      indexes={
 *          @ORM\Index(name="name", columns={"name"}),
 *      }
 * )
 */
class UnicatRepository
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Пространство имен сущностей, например: Demo\SiteBundle\Entity\Catalog\
     *
     * @ORM\Column(type="string")
     */
    protected $entities_namespace;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * Включает записи вложенных категорий.
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_inheritance;

    /**
     * Медиаколлекция по умолчанию.
     *
     * @ORM\Column(type="integer")
     */
    protected $media_collection_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var UnicatStructure[]
     *
     * @ORM\OneToMany(targetEntity="UnicatStructure", mappedBy="repository")
     */
    protected $structures;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_inheritance = true;
        $this->entities_namespace = null;
        $this->structures = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title . ' (' . $this->entities_namespace . ')';
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
     * @param mixed $entities_namespace
     * @return $this
     */
    public function setEntitiesNamespace($entities_namespace)
    {
        $this->entities_namespace = $entities_namespace;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntitiesNamespace()
    {
        return $this->entities_namespace;
    }

    /**
     * @param mixed $is_inheritance
     * @return $this
     */
    public function setIsInheritance($is_inheritance)
    {
        $this->is_inheritance = $is_inheritance;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsInheritance()
    {
        return $this->is_inheritance;
    }

    /**
     * @param mixed $media_collection_id
     * @return $this
     */
    public function setMediaCollectionId($media_collection_id)
    {
        $this->media_collection_id = $media_collection_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaCollectionId()
    {
        return $this->media_collection_id;
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
     * @param mixed $structures
     * @return $this
     */
    public function setStructures($structures)
    {
        $this->structures = $structures;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStructures()
    {
        return $this->structures;
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
