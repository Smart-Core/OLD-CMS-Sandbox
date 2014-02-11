<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_items")
 */
class ItemModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_enabled;

    /**
     * ORM\ManyToMany(targetEntity="Category", inversedBy="items", cascade={"persist"})
     * ORM\JoinTable(name="unicat_items_categories")
     */
    protected $categories;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $slug;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $properties;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $is_enabled
     * @return $this
     */
    public function setIsEnabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
