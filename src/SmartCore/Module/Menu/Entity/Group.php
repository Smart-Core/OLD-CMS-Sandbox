<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="menu_groups")
 */
class Group
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\UserId;

    /**
     * @var Item[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="group", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    protected $items;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $properties;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->position     = 0;
        $this->description  = null;
        $this->items        = new ArrayCollection();
        $this->user_id      = 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
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
     * @param string $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return string
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param Item[] $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
