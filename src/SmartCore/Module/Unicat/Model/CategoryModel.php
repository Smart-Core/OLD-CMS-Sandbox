<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Module\Unicat\Entity\UnicatStructure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_categories"
 *      indexes={
 *          ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          ORM\Index(name="position",   columns={"position"})
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
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Position;
    use ColumnTrait\Title;
    use ColumnTrait\UserId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $slug;

    /**
     * Включает записи вложенных категорий.
     *
     * @ORM\Column(type="boolean", options={"default:1"})
     */
    protected $is_inheritance;

    /**
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $properties;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
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
     * @ORM\ManyToOne(targetEntity="SmartCore\Module\Unicat\Entity\UnicatStructure")
     **/
    protected $structure;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categories", fetch="EXTRA_LAZY")
     */
    protected $items;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categoriesSingle", fetch="EXTRA_LAZY")
     */
    protected $itemsSingle;

    /**
     * Для отображения в формах. Не маппится в БД.
     */
    protected $form_title = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at       = new \DateTime();
        $this->is_enabled       = true;
        $this->is_inheritance   = true;
        $this->meta             = [];
        $this->position         = 0;
        $this->properties       = null;
        $this->user_id          = 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
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
     * @param CategoryModel|null  $parent
     * @return $this
     */
    public function setParent(CategoryModel $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return CategoryModel
     */
    public function getParent()
    {
        return $this->parent;
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
        foreach ($meta as $name => $value) {
            if (empty($value)) {
                unset($meta[$name]);
            }
        }

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
     * @param array $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

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
     * @return array
     */
    public function getProperties()
    {
        return empty($this->properties) ? [] : $this->properties;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getProperty($name)
    {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]) ? true : false;
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

    /**
     * @param string $form_title
     * @return $this
     */
    public function setFormTitle($form_title)
    {
        $this->form_title = $form_title;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormTitle()
    {
        return $this->form_title;
    }

    /**
     * Получить полный путь, включая родительские категории.
     *
     * @return string
     */
    public function getSlugFull()
    {
        $slug = $this->getSlug();

        if ($this->getParent()) {
            $slug  = $this->getParent()->getSlugFull().'/'.$slug;
        }

        return $slug;
    }
}
