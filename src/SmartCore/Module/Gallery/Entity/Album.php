<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;
use SmartCore\Bundle\CMSBundle\Model\IsEnabledTrait;
use SmartCore\Bundle\CMSBundle\Model\SignedTrait;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="gallery_albums")
 */
class Album
{
    use CreatedAtTrait;
    use IsEnabledTrait;
    use SignedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descr;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $cover_image_id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $photos_count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="albums")
     */
    protected $gallery;

    /**
     * @var Photo[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="album")
     */
    protected $photos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->updated_at   = new \DateTime();
        $this->photos_count = 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $cover_image_id
     * @return $this
     */
    public function setCoverImageId($cover_image_id)
    {
        $this->cover_image_id = $cover_image_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getCoverImageId()
    {
        return $this->cover_image_id;
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
     * @param Gallery $gallery
     * @return $this
     */
    public function setGallery(Gallery $gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param Photo[]|ArrayCollection $photos
     * @return $this
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @return Photo[]|ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param int $photos_count
     * @return $this
     */
    public function setPhotosCount($photos_count)
    {
        $this->photos_count = $photos_count;

        return $this;
    }

    /**
     * @return int
     */
    public function getPhotosCount()
    {
        return $this->photos_count;
    }

    /**
     * @param \DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function lastUpdatedAt()
    {
        $this->updated_at = new \DateTime();
    }
}
