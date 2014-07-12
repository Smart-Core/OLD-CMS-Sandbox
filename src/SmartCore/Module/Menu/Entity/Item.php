<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Entity\Folder;

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
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="children")
     * @ORM\JoinColumn(name="pid")
     */
    protected $parent_item;

    /**
     * @var Item[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parent_item")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $position;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="items")
     */
    protected $group;

    /**
     * @var Folder
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\CMSBundle\Entity\Folder")
     */
    protected $folder;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $descr;

    /**
     * Custom url.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $properties;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $create_by_user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->children          = new ArrayCollection();
        $this->create_by_user_id = 0;
        $this->created_at        = new \DateTime();
        $this->is_active         = true;
        $this->parent_item       = null;
        $this->position          = 0;
        $this->properties        = null;
        $this->title             = null;
        $this->url               = null;
        $this->updated_at        = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();
        if (empty($title)) {
            $title = (null === $this->getFolder()) ? $this->getId() : $this->getFolder()->getTitle();
        }

        return (string) $title;
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
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * @param Folder|null $folder
     * @return $this
     */
    public function setFolder(Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * @return Folder|null
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function setGroup(Group $group)
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
     * @return Item[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
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
     * @param array|null $properties
     * @return $this
     */
    public function setProperties(array $properties = null)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return array|null
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
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
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

        if (null == $parent) {
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
     * @ORM\PreUpdate()
     */
    public function onUpdated()
    {
        $this->updated_at = new \DateTime();
    }
}
