<?php

namespace SmartCore\Module\Blog\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use SmartCore\Module\Blog\Model\CategoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Category extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return ItemInterface
     */
    public function tree(FactoryInterface $factory, array $options)
    {
        if (!isset($options['categoryClass'])) {
            throw new \Exception('Надо указать categoryClass в опциях');
        }

        $menu = $factory->createItem('categories');
        $this->addChild($menu, null, $options['categoryClass']);
        $this->removeFactory($menu);

        return $menu;
    }

    /**
     * Рекурсивный метод для удаления фабрики, что позволяет кешировать объект меню.
     *
     * @param ItemInterface $menu
     */
    protected function removeFactory(ItemInterface $menu)
    {
        $menu->setFactory(new DummyFactory());

        foreach ($menu->getChildren() as $subMenu) {
            $this->removeFactory($subMenu);
        }
    }

    /**
     * Рекурсивное построение дерева.
     *
     * @param ItemInterface $menu
     * @param CategoryInterface|null $parent
     * @param string $categoryClass
     */
    protected function addChild(ItemInterface $menu, CategoryInterface $parent = null, $categoryClass)
    {
        $categories = $parent
            ? $parent->getChildren()
            : $this->container->get('doctrine')->getManager()->getRepository($categoryClass)->findBy(['parent' => null]);

        /** @var CategoryInterface $category */
        foreach ($categories as $category) {
            $uri = $this->container->get('router')->generate('smart_blog.category.articles', ['slug' => $category->getSlugFull()]).'/';
            $menu->addChild($category->getTitle(), ['uri' => $uri])
                ->setAttributes([
                    'class' => 'folder',
                    'id'    => 'category_id_'.$category->getId(),
                ]);

            /** @var ItemInterface $sub_menu */
            $sub_menu = $menu[$category->getTitle()];

            $this->addChild($sub_menu, $category, $categoryClass);
        }
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return ItemInterface
     */
    public function adminTree(FactoryInterface $factory, array $options)
    {
        if (!isset($options['categoryClass'])) {
            throw new \Exception('Надо указать categoryClass в опциях');
        }

        $menu = $factory->createItem('categories');
        $this->addChildToAdminTree($menu, null, $options['categoryClass']);

        return $menu;
    }

    /**
     * Рекурсивное построение дерева для админки.
     *
     * @param ItemInterface $menu
     * @param CategoryInterface|null $parent
     * @param string $categoryClass
     */
    protected function addChildToAdminTree(ItemInterface $menu, CategoryInterface $parent = null, $categoryClass)
    {
        $categories = $parent
            ? $parent->getChildren()
            : $this->container->get('doctrine')->getManager()->getRepository($categoryClass)->findBy(['parent' => null]);

        /** @var CategoryInterface $category */
        foreach ($categories as $category) {
            $uri = $this->container->get('router')->generate('smart_blog_admin_category_edit', ['id' => $category->getId()]);
            $menu->addChild($category->getTitle(), ['uri' => $uri])
                ->setAttributes([
                    'class' => 'folder',
                    'id'    => 'category_id_'.$category->getId(),
                ]);

            /** @var ItemInterface $sub_menu */
            $sub_menu = $menu[$category->getTitle()];

            $this->addChildToAdminTree($sub_menu, $category, $categoryClass);
        }
    }
}
