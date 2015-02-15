<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_items",
 *      indexes={
 *          ORM\Index(name="position", columns={"position"}),
 *      }
 * )
 * @UniqueEntity(fields={"slug", "slug"}, message="Запись с таким сегментом URI уже существует.")
 */
class ItemModel
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Position;
    use ColumnTrait\UserId;

    /**
     * @var CategoryModel[]
     *
     * ORM\ManyToMany(targetEntity="Category", inversedBy="items", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * ORM\JoinTable(name="unicat_items_categories_relations")
     */
    protected $categories;

    /**
     * @var CategoryModel[]
     *
     * ORM\ManyToMany(targetEntity="Category", inversedBy="itemsSingle", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * ORM\JoinTable(name="unicat_items_categories_relations_single")
     */
    protected $categoriesSingle;

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
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->position   = 0;
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

        return;
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
     * @param mixed $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return CategoryModel[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param CategoryModel[] $categoriesSingle
     * @return $this
     */
    public function setCategoriesSingle($categoriesSingle)
    {
        $this->categoriesSingle = $categoriesSingle;

        return $this;
    }

    /**
     * @return CategoryModel[]
     */
    public function getCategoriesSingle()
    {
        return $this->categoriesSingle;
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
}
