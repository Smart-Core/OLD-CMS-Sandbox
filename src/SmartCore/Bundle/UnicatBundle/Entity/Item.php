<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="unicat_items")
 * @ORM\Entity()
 */
class Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     *
     * @var boolean
     */
    protected $isEnabled;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id")
     */
    protected $category;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    protected $slug;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @todo
     * @var ItemProperty
     */
    protected $properties;

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
     * @param boolean $isEnabled
     * @return $this
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
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
     * @param string $uriPart
     * @return Item
     */
    public function setUriPart($uriPart)
    {
        $this->uriPart = $uriPart;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getUriPart()
    {
        return $this->uriPart;
    }

    /**
     * @param array $meta
     * @return Item
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
