<?php

namespace SmartCore\Module\Blog\Event;

use SmartCore\Module\Blog\Model\ArticleInterface;
use Symfony\Component\EventDispatcher\Event;

class FilterArticleEvent extends Event
{
    /**
     * @var ArticleInterface
     */
    protected $article;

    /**
     * Constructor.
     *
     * @param ArticleInterface $article
     */
    public function __construct(ArticleInterface $article)
    {
        $this->article = $article;
    }

    /**
     * @return ArticleInterface
     */
    public function getArticle()
    {
        return $this->article;
    }
}
