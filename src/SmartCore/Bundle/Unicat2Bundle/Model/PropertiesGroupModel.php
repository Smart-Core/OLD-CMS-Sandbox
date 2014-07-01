<?php

namespace SmartCore\Bundle\Unicat2Bundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Entity\UnicatRepository;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat2_properties_groups")
 */
class PropertiesGroupModel
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
     * @var ItemModel
     *
     * @ORM\ManyToOne(targetEntity="Item")
     **/
    protected $item;

    /**
     * @var UnicatRepository
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\Unicat2Bundle\Entity\UnicatRepository")
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
     * @param ItemModel $item
     * @return $this
     */
    public function setItem(ItemModel $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return ItemModel
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param PropertyModel[] $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return PropertyModel[]
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
