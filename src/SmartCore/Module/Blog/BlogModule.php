<?php

namespace SmartCore\Module\Blog;

use SmartCore\Bundle\CMSBundle\Module\Bundle;

class BlogModule extends Bundle
{
    /**
     * Получить виджеты для рабочего стола.
     *
     * @return array
     */
    public function getDashboard()
    {
        $r = $this->container->get('router');

        $data = [
            'title' => 'Блог',
            'items' => [
                'new' => [
                    'title' => 'Написать статью',
                    'descr' => '',
                    'url' => $r->generate('smart_blog_admin_article_create'),
                ],
                'tags' => [
                    'title' => 'Тэги',
                    'descr' => '',
                    'url' => $r->generate('smart_blog_admin_tag'),
                ],
                'categories' => [
                    'title' => 'Категории',
                    'descr' => '',
                    'url' => $r->generate('smart_blog_admin_category'),
                ],
            ],
        ];

        return $data;
    }
}
