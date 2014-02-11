<?php

namespace SmartCore\Module\Slider\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="slides",
 *      indexes={
 *          @ORM\Index(name="position", columns={"position"}),
 *          @ORM\Index(name="user_id",  columns={"user_id"}),
 *      }
 * )
 */
class Slide
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @Assert\File(maxSize="2000000")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(length=64, unique=true)
     */
    private $file_name;

    /**
     * @var string
     *
     * @ORM\Column(length=255)
     */
    private $original_file_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $properties;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @var Slider
     *
     * @ORM\ManyToOne(targetEntity="Slider", inversedBy="slides")
     * @ORM\JoinColumn(name="slider_id", nullable=false)
     * @Assert\NotBlank()
     */
    private $slider;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt    = new \DateTime();
        $this->enabled      = true;
        $this->position     = 0;
        $this->properties   = [];
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $file_name
     * @return $this
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param string $original_file_name
     * @return $this
     */
    public function setOriginalFileName($original_file_name)
    {
        $this->original_file_name = $original_file_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalFileName()
    {
        return $this->original_file_name;
    }

    /**
     * @param \SmartCore\Module\Slider\Entity\Slider $slider
     * @return $this
     */
    public function setSlider(Slider $slider)
    {
        $this->slider = $slider;

        return $this;
    }

    /**
     * @return \SmartCore\Module\Slider\Entity\Slider
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param string $title
     * @return Slide
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
     * @param string $createdAt
     * @return Slide
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param boolean $enabled
     * @return Slide
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        if (null == $position) {
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
     * @param mixed $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param  int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
