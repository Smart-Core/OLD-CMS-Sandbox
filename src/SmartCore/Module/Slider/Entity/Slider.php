<?php

namespace SmartCore\Module\Slider\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sliders")
 */
class Slider
{
    use ColumnTrait\Id;
    use ColumnTrait\Title;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $width;

    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $pause_time;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $slide_properties;

    /**
     * @var Slide[]
     *
     * @ORM\OneToMany(targetEntity="Slide", mappedBy="slider")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $slides;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->pause_time = 5000;
        $this->slides = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @param int $width
     *
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
     *
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
     *
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
     *
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
     * @param int $pause_time
     *
     * @return $this
     */
    public function setPauseTime($pause_time)
    {
        $this->pause_time = $pause_time;

        return $this;
    }

    /**
     * @return int
     */
    public function getPauseTime()
    {
        return $this->pause_time;
    }

    /**
     * @param int $height
     *
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
     * @param \SmartCore\Module\Slider\Entity\Slide[] $slides
     *
     * @return $this
     */
    public function setSlides($slides)
    {
        $this->slides = $slides;

        return $this;
    }

    /**
     * @return \SmartCore\Module\Slider\Entity\Slide[]
     */
    public function getSlides()
    {
        return $this->slides;
    }
}
