<?php

namespace SmartCore\Module\Blog\Model;

interface CategorizedInterface
{
    /**
     * @return CategoryInterface|null
     */
    public function getCategory();

    /**
     * @param CategoryInterface|null $category
     * @return $this
     */
    public function setCategory(CategoryInterface $category = null);
}
