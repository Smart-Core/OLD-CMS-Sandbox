<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_repositories")
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
     * Имя бандла для хранения сущностей.
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $bundle;

    /**
     * Возможность использовать конструкцию вида: SiteBundle:News\Category, где "News" является namespace.
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $namespace;

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
        $this->namespace = null;
        $this->structures = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
