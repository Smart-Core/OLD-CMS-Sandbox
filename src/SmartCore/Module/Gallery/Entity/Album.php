<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="gallery_albums")
 */
class Album
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\Title;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\UserId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $cover_image_id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $last_image_id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $photos_count;

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
        $this->position     = 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param int $cover_image_id
     *
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
     * @param Gallery $gallery
     *
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
     *
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
     *
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
     * @ORM\PreUpdate()
     */
    public function lastUpdatedAt()
    {
        $this->updated_at = new \DateTime();
    }

    /**
     * @param int $last_image_id
     *
     * @return $this
     */
    public function setLastImageId($last_image_id)
    {
        $this->last_image_id = $last_image_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastImageId()
    {
        return $this->last_image_id;
    }
}
