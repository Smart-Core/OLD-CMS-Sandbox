<?php

namespace SmartCore\Module\Blog;

abstract class Events
{
    public static function articleCreate()
    {
        return static::$REFIX.self::ARTICLE_CREATE;
    }

    /**
     * The `CATEGORY_CREATE` event is thrown each time a category is created in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCategoryEvent instance.
     */
    const CATEGORY_CREATE = 'smart_blog.category.create';

    /**
     * The `CATEGORY_PRE_UPDATE` event is thrown before each time a category is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCategoryEvent instance.
     */
    const CATEGORY_PRE_UPDATE = 'smart_blog.category.pre_save';

    /**
     * The `CATEGORY_POST_UPDATE` event is thrown after each time a category is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCategoryEvent instance.
     */
    const CATEGORY_POST_UPDATE = 'smart_blog.category.post_save';

    /**
     * The `CATEGORY_PRE_DELETE` event is thrown before each time a category is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCategoryEvent instance.
     */
    const CATEGORY_PRE_DELETE = 'smart_blog.category.pre_delete';

    /**
     * The `CATEGORY_POST_DELETE` event is thrown after each time a category is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCategoryEvent instance.
     */
    const CATEGORY_POST_DELETE = 'smart_blog.category.post_delete';

    /**
     * The `COMMENT_CREATE` event is thrown each time a comment is created in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCommentEvent instance.
     */
    const COMMENT_CREATE = 'smart_blog.comment.create';

    /**
     * The `COMMENT_PRE_UPDATE` event is thrown before each time a comment is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCommentEvent instance.
     */
    const COMMENT_PRE_UPDATE = 'smart_blog.comment.pre_save';

    /**
     * The `COMMENT_POST_UPDATE` event is thrown after each time a comment is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCommentEvent instance.
     */
    const COMMENT_POST_UPDATE = 'smart_blog.comment.post_save';

    /**
     * The `COMMENT_PRE_DELETE` event is thrown before each time a comment is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCommentEvent instance.
     */
    const COMMENT_PRE_DELETE = 'smart_blog.comment.pre_delete';

    /**
     * The `COMMENT_POST_DELETE` event is thrown after each time a comment is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCommentEvent instance.
     */
    const COMMENT_POST_DELETE = 'smart_blog.comment.post_delete';

    /**
     * The `ARTICLE_CREATE` event is thrown each time a post is created in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterPostEvent instance.
     */
    const ARTICLE_CREATE = 'smart_blog.article.create';

    /**
     * The `ARTICLE_PRE_UPDATE` event is thrown before each time a post is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterPostEvent instance.
     */
    const ARTICLE_PRE_UPDATE = 'smart_blog.article.pre_save';

    /**
     * The `ARTICLE_POST_UPDATE` event is thrown after each time a post is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterPostEvent instance.
     */
    const ARTICLE_POST_UPDATE = 'smart_blog.article.post_save';

    /**
     * The `ARTICLE_PRE_DELETE` event is thrown before each time a post is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterPostEvent instance.
     */
    const ARTICLE_PRE_DELETE = 'smart_blog.article.pre_delete';

    /**
     * The `ARTICLE_POST_DELETE` event is thrown after each time a post is deleted
     * in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterPostEvent instance.
     */
    const ARTICLE_POST_DELETE = 'smart_blog.article.post_delete';

    /**
     * The `TAG_CREATE` event is thrown each time a tag is created in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterCOMMENTEvent instance.
     */
    const TAG_CREATE = 'smart_blog.tag.create';

    /**
     * The `TAG_PRE_UPDATE` event is thrown before each time a tag is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterTagEvent instance.
     */
    const TAG_PRE_UPDATE = 'smart_blog.tag.pre_save';

    /**
     * The `TAG_POST_UPDATE` event is thrown after each time a tag is saved in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterTagEvent instance.
     */
    const TAG_POST_UPDATE = 'smart_blog.tag.post_save';

    /**
     * The `TAG_PRE_DELETE` event is thrown before each time a tag is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterTagEvent instance.
     */
    const TAG_PRE_DELETE = 'smart_blog.tag.pre_delete';

    /**
     * The `TAG_POST_DELETE` event is thrown after each time a tag is deleted in the system.
     *
     * The event listener receives an SmartCore\Module\Blog\Event\FilterTagEvent instance.
     */
    const TAG_POST_DELETE = 'smart_blog.tag.post_delete';
}
