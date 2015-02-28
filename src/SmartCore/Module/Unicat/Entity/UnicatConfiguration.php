<?php

namespace SmartCore\Module\Unicat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Bundle\MediaBundle\Entity\Collection;
use SmartCore\Module\Unicat\Model\CategoryModel;
use SmartCore\Module\Unicat\Model\PropertiesGroupModel;
use SmartCore\Module\Unicat\Model\PropertyModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_configurations",
 *      indexes={
 *          @ORM\Index(columns={"name"}),
 *      }
 * )
 */
class UnicatConfiguration
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Name;
    use ColumnTrait\Title;
    use ColumnTrait\UserId;

    /**
     * Пространство имен сущностей, например: DemoSiteBundle\Entity\Catalog\
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $entities_namespace;

    /**
     * Включает записи вложенных категорий.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_inheritance;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\MediaBundle\Entity\Collection")
     */
    protected $media_collection;

    /**
     * @var UnicatStructure
     *
     * @ORM\ManyToOne(targetEntity="UnicatStructure")
     */
    protected $default_structure;

    /**
     * @var UnicatStructure[]
     *
     * @ORM\OneToMany(targetEntity="UnicatStructure", mappedBy="configuration")
     */
    protected $structures;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at           = new \DateTime();
        $this->is_inheritance       = true;
        $this->entities_namespace   = null;
        $this->structures           = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title.' ('.$this->entities_namespace.')';
    }

    /**
     * @return string
     */
    public function getCategoryClass()
    {
        return $this->entities_namespace.'Category';
    }

    /**
     * @return string
     */
    public function getItemClass()
    {
        return $this->entities_namespace.'Item';
    }

    /**
     * @return string
     */
    public function getPropertyClass()
    {
        return $this->entities_namespace.'Property';
    }

    /**
     * @return string
     */
    public function getPropertiesGroupClass()
    {
        return $this->entities_namespace.'PropertiesGroup';
    }

    /**
     * @return CategoryModel
     *
     * @deprecated
     */
    public function createCategory()
    {
        $class = $this->getCategoryClass();

        return new $class();
    }

    /**
     * @return PropertyModel
     *
     * @deprecated
     */
    public function createProperty()
    {
        $class = $this->getPropertyClass();

        return new $class();
    }

    /**
     * @return PropertiesGroupModel
     *
     * @deprecated
     */
    public function createPropertiesGroup()
    {
        $class = $this->getPropertiesGroupClass();

        return new $class();
    }

    /**
     * @param string $entities_namespace
     * @return $this
     */
    public function setEntitiesNamespace($entities_namespace)
    {
        $this->entities_namespace = $entities_namespace;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntitiesNamespace()
    {
        return $this->entities_namespace;
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
     * @param Collection|null $media_collection
     * @return $this
     */
    public function setMediaCollection(Collection $media_collection = null)
    {
        $this->media_collection = $media_collection;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getMediaCollection()
    {
        return $this->media_collection;
    }

    /**
     * @param UnicatStructure[]|ArrayCollection $structures
     * @return $this
     */
    public function setStructures($structures)
    {
        $this->structures = $structures;

        return $this;
    }

    /**
     * @return UnicatStructure[]
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * @param UnicatStructure $default_structure
     * @return $this
     */
    public function setDefaultStructure(UnicatStructure $default_structure)
    {
        $this->default_structure = $default_structure;

        return $this;
    }

    /**
     * @return UnicatStructure
     */
    public function getDefaultStructure()
    {
        return $this->default_structure;
    }
}
