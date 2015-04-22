<?php

namespace SmartCore\Module\Blog\Model;

interface ArticleInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $annotation
     *
     * @return $this
     */
    public function setAnnotation($annotation);

    /**
     * @return string
     */
    public function getAnnotation();

    /**
     * @return \Datetime
     */
    public function getCreatedAt();

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $keywords
     *
     * @return $this
     */
    public function setKeywords($keywords);

    /**
     * @return string
     */
    public function getKeywords();

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setIsEnabled($enabled);

    /**
     * @return bool
     */
    public function getIsEnabled();

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text);

    /**
     * @return string
     */
    public function getText();

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt();

    /**
     * @return $this
     */
    public function setUpdatedAt();

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getSlug();
}
