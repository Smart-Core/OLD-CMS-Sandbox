<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_properties_groups")
 */
class PropertyGroupModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

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
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->properties = new ArrayCollection();
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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
