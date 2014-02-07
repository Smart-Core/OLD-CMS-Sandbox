<?php

namespace SmartCore\Module\Blog\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class AdminMenu extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function main(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('smart_blog_admin');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-pills');

        // @todo определение подключены ли категории и тэги.
        $menu->addChild('Articles',     ['route' => 'smart_blog_admin_article']);
        $menu->addChild('Tags',         ['route' => 'smart_blog_admin_tag']);
        $menu->addChild('Categories',   ['route' => 'smart_blog_admin_category']);

        return $menu;
    }
}
