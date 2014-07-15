<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;
use SmartCore\Bundle\CMSBundle\Model\PositionTrait;
use SmartCore\Bundle\CMSBundle\Model\SignedTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="PhotoRepository")
 * @ORM\Table(name="gallery_photos")
 */
class Photo
{
    use CreatedAtTrait;
    use SignedTrait;
    use PositionTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descr;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \SmartCore\Module\Gallery\Entity\Album $album
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
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
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
