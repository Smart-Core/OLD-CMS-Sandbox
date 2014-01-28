<?php

namespace SmartCore\Bundle\FelibBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="front_end_libraries")
 * @ORM\Entity(repositoryClass="SmartCore\Bundle\FelibBundle\Entity\LibraryRepository")
 */
class Library
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(length=30, unique=TRUE)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(length=16, nullable=TRUE)
     * @var string
     */
    protected $related_by;

    /**
     * @ORM\Column(type="smallint")
     * @var int
     */
    protected $proirity;

    /**
     * @ORM\Column(length=16)
     * @var string
     */
    protected $current_version;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $files;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proirity = 0;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @param int $proirity
     * @return $this
     */
    public function setProirity($proirity)
    {
        $this->proirity = $proirity;

        return $this;
    }

    /**
     * @return int
     */
    public function getProirity()
    {
        return $this->proirity;
    }

    /**
     * @param string $related_by
     * @return $this
     */
    public function setRelatedBy($related_by)
    {
        $this->related_by = $related_by;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelatedBy()
    {
        return $this->related_by;
    }

    /**
     * @param string $current_version
     * @return $this
     */
    public function setCurrentVersion($current_version)
    {
        $this->current_version = $current_version;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->current_version;
    }

    /**
     * @param string $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return string
     */
    public function getFiles()
    {
        return $this->files;
    }

}
