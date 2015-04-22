<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PhotoRepository")
 * @ORM\Table(name="gallery_photos")
 */
class Photo
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\UserId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $image_id;

    /**
     * @var Album
     *
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="photos", fetch="EXTRA_LAZY")
     */
    protected $album;

    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *      maxSize = "5M",
     *      mimeTypes = {"image/jpeg", "image/png", "image/gif"}
     * )
     */
    protected $file;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @param \SmartCore\Module\Gallery\Entity\Album $album
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return \SmartCore\Module\Gallery\Entity\Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param int $image_id
     *
     * @return $this
     */
    public function setImageId($image_id)
    {
        $this->image_id = $image_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageId()
    {
        return $this->image_id;
    }
}
