<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_categories"
 *      indexes={
 *          ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          ORM\Index(name="position", columns={"position"})
 *      },
 *      uniqueConstraints={
 *          ORM\UniqueConstraint(name="slug_parent_structure", columns={"slug", "parent_id", "structure_id"}),
 *      }
 * )
 *
 * @UniqueEntity(fields={"slug", "parent", "structure"}, message="В каждой подкатегории должен быть уникальный сегмент URI")
 */
abstract class CategoryModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_enabled;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_inheritance;

    /**
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @var CategoryModel
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", cascade={"persist"})
     **/
    protected $parent;

    /**
     * @var UnicatStructure
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure")
     **/
    protected $structure;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categories")
     */
    protected $items;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categories")
     */
    protected $itemsSingle;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->is_inheritance = true;
        $this->meta = null;
        $this->position = 0;
        $this->user_id = 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return int
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
     * @param bool $is_enabled
     * @return $this
     */
    public function setIsEnabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
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
     * @param mixed $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
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
     * @param mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
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
     * @param UnicatStructure $structure
     * @return $this
     */
    public function setStructure(UnicatStructure $structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return UnicatStructure
     */
    public function getStructure()
    {
        return $this->structure;
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

    /**
     * @param mixed $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }
}
