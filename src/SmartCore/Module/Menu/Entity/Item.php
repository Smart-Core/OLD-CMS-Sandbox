<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ItemRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="menu_items")
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
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\EngineBundle\Entity\Folder")
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
        $this->children = new ArrayCollection();
        $this->create_by_user_id = 0;
        $this->created = new \DateTime();
        $this->is_active = true;
        $this->parent_item = null;
        $this->position = 0;
        $this->title = null;
        $this->url = null;
        $this->updated = null;
    }

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

    public function getId()
    {
        return $this->item_id;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
        return $this;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * @return \SmartCore\Bundle\EngineBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

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

    public function getChildren()
    {
        return $this->children;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
        return $this;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setPosition($position)
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = $position;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setParentItem($parent_item)
    {
        if (empty($parent_item) or $parent_item->getId() == $this->getId()) {
            $this->parent_item = null;
        } else {
            $this->parent_item = $parent_item;
        }

        return $this;
    }

    public function getParentItem()
    {
        return $this->parent_item;
    }

    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;
        return $this;
    }

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
