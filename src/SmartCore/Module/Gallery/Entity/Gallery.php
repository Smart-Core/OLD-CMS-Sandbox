<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Bundle\MediaBundle\Entity\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="galleries")
 */
class Gallery
{
    const ORDER_BY_CREATED    = 0;
    const ORDER_BY_POSITION   = 1;
    const ORDER_BY_LAST_PHOTO = 2; // @todo

    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Title;
    use ColumnTrait\UserId;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $order_albums_by;

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
        $this->albums           = new ArrayCollection();
        $this->created_at       = new \DateTime();
        $this->order_albums_by  = self::ORDER_BY_CREATED;
    }

    /**
     * @return string
     */
    public function __toString()
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

    /**
     * @param string $order_albums_by
     * @return $this
     */
    public function setOrderAlbumsBy($order_albums_by)
    {
        $this->order_albums_by = $order_albums_by;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderAlbumsBy()
    {
        return $this->order_albums_by;
    }
}
