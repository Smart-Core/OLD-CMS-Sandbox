<?php

namespace SmartCore\Module\SimpleNews\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NewsRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="simple_news",
 *      indexes={
 *          @ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          @ORM\Index(name="created_at", columns={"created_at"}),
 *          @ORM\Index(name="publish_date", columns={"publish_date"}),
 *          @ORM\Index(name="end_publish_date", columns={"end_publish_date"}),
 *      }
 * )
 * @UniqueEntity(fields={"slug"}, message="URL должно быть уникальным, оно используется в строке запроса.")
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_enabled;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $image_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

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
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * Created datetime
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

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
     * Last updated datetime
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
     * @param bool $is_enabled
     * @return $this
     */
    public function setIsEnabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->is_enabled;
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
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM\PreUpdate
     */
    public function doStuffOnPreUpdate()
    {
        $this->updated_at = new \DateTime();
    }
}
