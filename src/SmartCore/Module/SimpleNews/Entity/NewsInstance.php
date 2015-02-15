<?php

namespace SmartCore\Module\SimpleNews\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Bundle\MediaBundle\Entity\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="simple_news_instances")
 * @UniqueEntity(fields={"name"}, message="Имя должно быть уникальным.")
 */
class NewsInstance
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\NameUnique;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $use_image;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $use_annotation_widget;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $use_end_publish_date;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\MediaBundle\Entity\Collection")
     */
    protected $media_collection;

    /**
     * @var News[]
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="instance")
     */
    protected $news;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->name       = new ArrayCollection();
        $this->use_image  = false;
        $this->use_annotation_widget = false;
        $this->use_end_publish_date  = false;
    }

    /**
     * @param News[]|ArrayCollection $news
     * @return $this
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * @return News[]
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param bool $use_annotation_widget
     * @return $this
     */
    public function setUseAnnotationWidget($use_annotation_widget)
    {
        $this->use_annotation_widget = $use_annotation_widget;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseAnnotationWidget()
    {
        return $this->use_annotation_widget;
    }

    /**
     * @return bool
     */
    public function isUseAnnotationWidget()
    {
        return $this->use_annotation_widget;
    }

    /**
     * @param bool $use_image
     * @return $this
     */
    public function setUseImage($use_image)
    {
        $this->use_image = $use_image;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseImage()
    {
        return $this->use_image;
    }

    /**
     * @return bool
     */
    public function isUseImage()
    {
        return $this->use_image;
    }

    /**
     * @param bool $use_end_publish_date
     * @return $this
     */
    public function setUseEndPublishDate($use_end_publish_date)
    {
        $this->use_end_publish_date = $use_end_publish_date;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseEndPublishDate()
    {
        return $this->use_end_publish_date;
    }

    /**
     * @return bool
     */
    public function isUseEndPublishDate()
    {
        return $this->use_end_publish_date;
    }

    /**
     * @param Collection $media_collection
     * @return $this
     */
    public function setMediaCollection(Collection $media_collection = null)
    {
        $this->media_collection = $media_collection;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMediaCollection()
    {
        return $this->media_collection;
    }
}
