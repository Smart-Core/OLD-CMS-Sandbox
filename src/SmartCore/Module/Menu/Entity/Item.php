<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ItemRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="menu")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $item_id;

    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_active;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="children")
     * @ORM\JoinColumn(name="pid", referencedColumnName="item_id")
     * -ORM\Column(nullable=TRUE)
     */
    protected $parent_item;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parent_item")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\Column(type="smallint", nullable=TRUE)
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="items")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
     */
    protected $group;

    /**
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\CMSBundle\Entity\Folder")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="folder_id")
     */
    protected $folder;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $descr;

    /**
     * Custom url.
     *
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $url;

    /**
     * @ORM\Column(type="array", nullable=TRUE)
     */
    protected $properties;

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

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->children          = new ArrayCollection();
        $this->create_by_user_id = 0;
        $this->created           = new \DateTime();
        $this->is_active         = true;
        $this->parent_item       = null;
        $this->position          = 0;
        $this->properties        = null;
        $this->title             = null;
        $this->url               = null;
        $this->updated           = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();
        if (empty($title)) {
            if ($this->getFolder() != null) {
                $title = $this->getFolder()->getTitle();
            } else {
                $title = $this->getId();
            }
        }

        return (string) $title;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->item_id;
    }

    /**
     * @param bool $is_active
     * @return $this
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param $folder
     * @return $this
     */
    public function setFolder($folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * @return \SmartCore\Bundle\CMSBundle\Entity\Folder|null
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return Item[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $descr
     * @return $this
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
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
        return empty($this->properties) ? [] : $this->properties;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param \Datetime $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \Datetime|null
     */
    public function getUpdated()
    {
        return $this->updated;
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

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Item|null $parent_item
     * @return $this
     */
    public function setParentItem($parent_item)
    {
        if (empty($parent_item) or $parent_item->getId() == $this->getId()) {
            $this->parent_item = null;
        } else {
            $this->parent_item = $parent_item;
        }

        return $this;
    }

    /**
     * @return Item|null
     */
    public function getParentItem()
    {
        return $this->parent_item;
    }

    /**
     * @param int $create_by_user_id
     * @return $this
     */
    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreateByUserId()
    {
        return $this->create_by_user_id;
    }

    /**
     * Защита от цикличных зависимостей.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function checkRelations()
    {
        $parent = $this->getParentItem();

        if(null == $parent) {
            return;
        }

        $cnt = 30;
        $ok = false;
        while ($cnt--) {
            if (null == $parent or $parent->getId() == 1) {
                $ok = true;
                break;
            } else {
                $parent = $parent->getParentItem();
                continue;
            }
        }

        // Если обнаружена циклическая зависимость, тогда родитель выставляется корневая папка, которая имеет id = 1.
        if (!$ok) {
            $this->setParentItem(null);
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdated()
    {
        $this->updated = new \DateTime();
    }
}
