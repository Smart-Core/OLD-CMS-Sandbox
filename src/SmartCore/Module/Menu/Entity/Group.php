<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="menu_groups")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $group_id;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="group", cascade={"persist", "remove"})
     */
    protected $items;

    /**
     * @ORM\Column(type="smallint", nullable=TRUE)
     * @Assert\Range(min = "-255", minMessage = "Минимальное значение -255.", max = "255", maxMessage = "Максимальное значение 255.")
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=50, nullable=FALSE, unique=TRUE)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $descr;

    /**
     * @ORM\Column(type="integer")
     */
    protected $create_by_user_id;

    /**
     * Created datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Last updated datetime
     *
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected $updated;

    public function __construct()
    {
        $this->create_by_user_id = 0;
        $this->created = new \DateTime();
        $this->updated = null;
        $this->position = 0;
        $this->descr = null;
        $this->items = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getId()
    {
        return $this->group_id;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPosition($position)
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;
    }

    public function getCreateByUserId()
    {
        return $this->create_by_user_id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdated()
    {
        $this->updated = new \DateTime();
    }
}
