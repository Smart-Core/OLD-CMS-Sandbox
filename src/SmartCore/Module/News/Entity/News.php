<?php

namespace SmartCore\Module\News\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NewsRepository")
 * @ORM\Table(name="news")
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
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $annotation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $annotation_widget;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * Created datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Дата публикации
     *
     * @ORM\Column(type="datetime")
     */
    protected $publish_date;

    /**
     * Дата окончания публикации
     *
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected $end_publish_date;

    /**
     * Last updated datetime
     *
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected $updated;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->is_enabled       = true;
        $this->created          = new \DateTime();
        $this->publish_date     = new \DateTime();
        $this->end_publish_date = null;
        $this->updated          = null;
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \Datetime|null $updated
     * @return $this
     */
    public function setUpdated(\DateTime $updated = null)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \Datetime|null
     */
    public function getUpdated()
    {
        return $this->updated;
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
        $this->slug = $slug;

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
}
