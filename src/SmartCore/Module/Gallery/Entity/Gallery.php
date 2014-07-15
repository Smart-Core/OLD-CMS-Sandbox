<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;
use SmartCore\Bundle\CMSBundle\Model\SignedTrait;
use SmartCore\Bundle\MediaBundle\Entity\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="galleries")
 */
class Gallery
{
    use CreatedAtTrait;
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
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    /**
     * @var Album[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Album", mappedBy="gallery")
     */
    protected $albums;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\MediaBundle\Entity\Collection")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $media_collection;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->albums       = new ArrayCollection();
        $this->created_at   = new \DateTime();
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
     * @param Album[]|ArrayCollection $albums
     * @return $this
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;

        return $this;
    }

    /**
     * @return Album[]|ArrayCollection
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param \SmartCore\Bundle\MediaBundle\Entity\Collection $media_collection
     * @return $this
     */
    public function setMediaCollection(Collection $media_collection)
    {
        $this->media_collection = $media_collection;

        return $this;
    }

    /**
     * @return \SmartCore\Bundle\MediaBundle\Entity\Collection
     */
    public function getMediaCollection()
    {
        return $this->media_collection;
    }
}
