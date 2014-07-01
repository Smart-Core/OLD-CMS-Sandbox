<?php

namespace SmartCore\Bundle\Unicat2Bundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Entity\UnicatStructure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat2_items",
 *      indexes={
 *          ORM\Index(name="position", columns={"position"}),
 *          ORM\Index(name="type", columns={"type"}),
 *      }
 * )
 * @UniqueEntity(fields={"slug", "slug"}, message="Запись с таким сегментом URI уже существует.")
 */
class ItemModel
{
    const TYPE_CATEGORY = 0;
    const TYPE_ITEM     = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_inheritance;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    /**
     * @var int
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $slug;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $properties;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var ItemModel[]
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @var ItemModel
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="children", cascade={"persist"})
     **/
    protected $parent;

    /**
     * @var UnicatStructure
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\Unicat2Bundle\Entity\UnicatStructure")
     **/
    protected $structure;

    /**
     * @var ItemModel[]
     *
     * ORM\ManyToMany(targetEntity="Category", inversedBy="items", cascade={"persist", "remove"})
     * ORM\JoinTable(name="unicat2_items_categories_relations")
     */
    protected $categories;

    /**
     * @var ItemModel[]
     *
     * ORM\ManyToMany(targetEntity="Category", inversedBy="itemsSingle", cascade={"persist", "remove"})
     * ORM\JoinTable(name="unicat2_items_categories_relations_single")
     */
    protected $categoriesSingle;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->is_inheritance = true;
        $this->position   = 0;
        $this->type       = self::TYPE_CATEGORY;
        $this->user_id    = 0;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (false !== strpos($name, 'structure:')) {
            $structureName = str_replace('structure:', '', $name);

            if ($this->categories->count() > 0) {
                $structureCollection = new ArrayCollection();

                foreach ($this->categories as $category) {
                    if ($category->getStructure()->getName() == $structureName) {
                        $structureCollection->add($category);
                    }
                }

                return $structureCollection;
            }
        }

        if (false !== strpos($name, 'property:')) {
            $properyName = str_replace('property:', '', $name);

            if (isset($this->properties[$properyName])) {
                return $this->properties[$properyName];
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function __set($name, $value)
    {
        if (false !== strpos($name, 'property:')) {
            $this->properties[str_replace('property:', '', $name)] = $value;
        }

        return $this;
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
     * @param ItemModel[]|ArrayCollection $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return ItemModel[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ItemModel[]|ArrayCollection $categoriesSingle
     * @return $this
     */
    public function setCategoriesSingle($categoriesSingle)
    {
        $this->categoriesSingle = $categoriesSingle;

        return $this;
    }

    /**
     * @return ItemModel[]
     */
    public function getCategoriesSingle()
    {
        return $this->categoriesSingle;
    }

    /**
     * @param boolean $is_enabled
     * @return $this
     */
    public function setIsEnabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return !$this->is_enabled;
    }

    /**
     * @param bool $is_inheritance
     * @return $this
     */
    public function setIsInheritance($is_inheritance)
    {
        $this->is_inheritance = $is_inheritance;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsInheritance()
    {
        return $this->is_inheritance;
    }

    /**
     * @return bool
     */
    public function isInheritance()
    {
        return $this->is_inheritance;
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
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getProperty($name)
    {
        return (isset($this->properties[$name])) ? $this->properties[$name] : null;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeProperty($name)
    {
        unset($this->properties[$name]);

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return (isset($this->properties[$name]) or null === @$this->properties[$name]) ? true : false;
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
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return empty($this->meta) ? [] : $this->meta;
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
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param ItemModel[]|ArrayCollection $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return ItemModel[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ItemModel $parent
     * @return $this
     */
    public function setParent(ItemModel $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return ItemModel
     */
    public function getParent()
    {
        return $this->parent;
    }
}
