<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * @ORM\Table(name="unicat_catalogs")
 */
class Catalog
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     *
     * @var boolean
     */
    protected $isEnabled;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $structures;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_inheritance", type="boolean")
     */
    protected $isInheritance;

    /**
     * Constructor.
     */
    public function __construct()
    {

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
     * @param string $name
     * @return Catalog
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
     * @param string $title
     * @return Catalog
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
     * @param array $structures
     * @return Catalog
     */
    public function setStructures($structures)
    {
        $this->structures = $structures;
    
        return $this;
    }

    /**
     * @return array
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * @param boolean $isInheritance
     * @return Catalog
     */
    public function setIsInheritance($isInheritance)
    {
        $this->isInheritance = $isInheritance;
    
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsInheritance()
    {
        return $this->isInheritance;
    }
}
