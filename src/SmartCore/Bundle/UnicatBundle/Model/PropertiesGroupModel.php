<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_properties_groups")
 */
class PropertiesGroupModel
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Name;
    use ColumnTrait\Title;

    /**
     * @var PropertyModel[]
     *
     * @ORM\OneToMany(targetEntity="Property", mappedBy="group")
     */
    protected $properties;

    /**
     * @var CategoryModel
     *
     * @ORM\ManyToOne(targetEntity="Category")
     **/
    protected $category;

    /**
     * @var UnicatRepository
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository")
     **/
    protected $repository;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->properties = new ArrayCollection();
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function setCategory(CategoryModel $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return CategoryModel
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \SmartCore\Bundle\UnicatBundle\Model\PropertyModel[] $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return \SmartCore\Bundle\UnicatBundle\Model\PropertyModel[]
     */
    public function getProperties()
    {
        return $this->properties;
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
}
