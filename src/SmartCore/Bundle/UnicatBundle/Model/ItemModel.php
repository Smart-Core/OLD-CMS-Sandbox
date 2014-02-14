<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_items")
 */
class ItemModel
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
     * @var CategoryModel[]
     *
     * ORM\ManyToMany(targetEntity="Category", inversedBy="items", cascade={"persist"})
     * ORM\JoinTable(name="unicat_items_categories")
     */
    protected $categories;

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
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->user_id = 0;
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
     * @param mixed $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
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
        return $this->meta;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
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
}
