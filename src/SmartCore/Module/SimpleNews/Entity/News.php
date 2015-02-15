<?php

namespace SmartCore\Module\SimpleNews\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NewsRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="simple_news",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"publish_date"}),
 *          @ORM\Index(columns={"end_publish_date"}),
 *      }
 * )
 * @UniqueEntity(fields={"slug"}, message="URL должно быть уникальным, оно используется в строке запроса.")
 */
class News
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\Title;
    use ColumnTrait\Text;

    /**
     * @var int|null
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $image_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $annotation;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $annotation_widget;

    /**
     * Дата публикации
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $publish_date;

    /**
     * Дата окончания публикации
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $end_publish_date;

    /**
     * @var NewsInstance
     *
     * @ORM\ManyToOne(targetEntity="NewsInstance", inversedBy="news")
     */
    protected $instance;

    /**
     * @var UploadedFile
     */
    protected $image;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->is_enabled       = true;
        $this->created_at       = new \DateTime();
        $this->publish_date     = new \DateTime();
        $this->end_publish_date = null;
        $this->updated_at       = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @param \DateTime $end_publish_date
     * @return $this
     */
    public function setEndPublishDate(\DateTime $end_publish_date)
    {
        $this->end_publish_date = $end_publish_date;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndPublishDate()
    {
        return $this->end_publish_date;
    }

    /**
     * @param \DateTime $publish_date
     * @return $this
     */
    public function setPublishDate(\DateTime $publish_date)
    {
        $this->publish_date = $publish_date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publish_date;
    }

    /**
     * @param int|null $image_id
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

    /**
     * @param UploadedFile|null $image
     * @return $this
     */
    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $annotation
     * @return $this
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @param string $annotation_widget
     * @return $this
     */
    public function setAnnotationWidget($annotation_widget)
    {
        $this->annotation_widget = $annotation_widget;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnnotationWidget()
    {
        return $this->annotation_widget;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = trim(str_replace('/', '', $slug));

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param NewsInstance $instance
     * @return $this
     */
    public function setInstance(NewsInstance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * @return NewsInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function doStuffOnPreUpdate()
    {
        $this->updated_at = new \DateTime();
    }
}
