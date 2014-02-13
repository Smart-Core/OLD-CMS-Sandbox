<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * @ORM\Table(name="unicat_catalogs")
 */
class CatalogModel
{
    /**
     * @var integer
     *
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
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="boolean")
     */
    protected $is_inheritance;

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
     * @param array $structures
     * @return $this
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
     * @return $this
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
