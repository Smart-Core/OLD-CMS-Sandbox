<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_categories",
 *      indexes={
 *          @ORM\Index(name="slug", columns={"slug"})
 *      }
 * )
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     *
     * @var Category
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", fetch="LAZY")
     * @ORM\OrderBy({"slug" = "ASC"})
     *
     * @var Category[]|ArrayCollection
     */
    protected $children;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="category")
     *
     * @var File[]|ArrayCollection
     */
    protected $files;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->files     = new ArrayCollection();
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
        return $this->createdAt;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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
}
