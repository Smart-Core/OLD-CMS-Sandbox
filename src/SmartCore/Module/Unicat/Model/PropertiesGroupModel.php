<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;

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
     * @var UnicatConfiguration
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Module\Unicat\Entity\UnicatConfiguration")
     **/
    protected $configuration;

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
     * @param \SmartCore\Module\Unicat\Model\PropertyModel[] $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return \SmartCore\Module\Unicat\Model\PropertyModel[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param UnicatConfiguration $configuration
     * @return $this
     */
    public function setConfiguration(UnicatConfiguration $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @return UnicatConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
