<?php

namespace SmartCore\Module\Slider\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sliders")
 */
class Slider
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $height;

    /**
     * Метод ресайза картинки (Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET или THUMBNAIL_OUTBOUND),
     * если указан, тогда применяется с указанными размерами.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $mode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $library;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $slide_properties;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $slide_properties
     * @return $this
     */
    public function setSlideProperties($slide_properties)
    {
        $this->slide_properties = $slide_properties;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlideProperties()
    {
        return $this->slide_properties;
    }

    /**
     * @param string $mode
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $library
     * @return $this
     */
    public function setLibrary($library)
    {
        $this->library = $library;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $title
     * @return Slider
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
}
