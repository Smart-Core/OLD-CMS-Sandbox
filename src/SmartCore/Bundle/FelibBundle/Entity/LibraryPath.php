<?php

namespace SmartCore\Bundle\FelibBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Bundle\FelibBundle\Entity\LibraryPathRepository")
 * @ORM\Table(name="front_end_libraries_paths",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="version_lib", columns={"version", "lib_id"}),
 *      }
 * )
 */
class LibraryPath
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $lib_id;

    /**
     * @ORM\Column(length=16)
     * @var string
     */
    protected $version;

    /**
     * @ORM\Column(length=255)
     * @var string
     */
    protected $path;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $lib_id
     * @return $this
     */
    public function setLibId($lib_id)
    {
        $this->lib_id = $lib_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getLibId()
    {
        return $this->lib_id;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
