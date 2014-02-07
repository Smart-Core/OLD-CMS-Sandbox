<?php

namespace SmartCore\Module\Blog\Event;

use SmartCore\Module\Blog\Model\CategoryInterface;
use Symfony\Component\EventDispatcher\Event;

class FilterCategoryEvent extends Event
{
    /**
     * @var CategoryInterface
     */
    protected $category;

    /**
     * Constructor.
     *
     * @param CategoryInterface $category
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    /**
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }
}
