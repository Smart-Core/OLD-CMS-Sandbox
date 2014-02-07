<?php

namespace SmartCore\Module\Blog\Repository;

use SmartCore\Module\Blog\Model\ArticleInterface;
use SmartCore\Module\Blog\Model\CategoryInterface;
use SmartCore\Module\Blog\Model\TagInterface;

interface ArticleRepositoryInterface
{
    /**
     * @param int|null $limit
     * @return ArticleInterface[]|null
     */
    public function findLast($limit = null);

    /**
     * @param TagInterface $tag
     * @return ArticleInterface[]|null
     */
    public function findByTag(TagInterface $tag);

    /**
     * @param CategoryInterface|null $category
     * @return \Doctrine\ORM\Query
     */
    public function getFindByCategoryQuery(CategoryInterface $category = null);

    /**
     * @param TagInterface $tag
     * @return \Doctrine\ORM\Query
     */
    public function getFindByTagQuery(TagInterface $tag);

    /**
     * @param CategoryInterface|null $category
     * @return int
     */
    public function getCountByCategory(CategoryInterface $category = null);
}
