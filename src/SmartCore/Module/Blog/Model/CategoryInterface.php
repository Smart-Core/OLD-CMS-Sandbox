<?php

namespace SmartCore\Module\Blog\Model;

interface CategoryInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Category[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildren();

    /**
     * @param CategoryInterface $parent
     * @return $this
     */
    public function setParent(CategoryInterface $parent);

    /**
     * @return CategoryInterface
     */
    public function getParent();

    /**
     * @return string
     */
    public function getSlugFull();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);
}
