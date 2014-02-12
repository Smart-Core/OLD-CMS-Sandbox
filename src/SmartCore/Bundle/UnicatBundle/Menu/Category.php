<?php

namespace SmartCore\Bundle\UnicatBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use Symfony\Component\DependencyInjection\ContainerAware;

class Category extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
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
     * @return void
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
     * @param CategoryModel|null $parent
     * @param string $categoryClass
     * @return void
     */
    protected function __addChild(ItemInterface $menu, CategoryModel $parent = null, $categoryClass) // @todo
    {
        $categories = $parent
            ? $parent->getChildren()
            : $this->container->get('doctrine')->getManager()->getRepository($categoryClass)->findBy(['parent' => null]);

        /** @var CategoryModel $category */
        foreach ($categories as $category) {
            $uri = $this->container->get('router')->generate('smart_module.catalog.category', ['slug' => $category->getSlugFull()]) . '/';
            $menu->addChild($category->getTitle(), ['uri' => $uri])
                ->setAttributes([
                    'class' => 'folder',
                    'id'    => 'category_id_' . $category->getId(),
                ]);

            /** @var ItemInterface $sub_menu */
            $sub_menu = $menu[$category->getTitle()];

            $this->addChild($sub_menu, $category, $categoryClass);
        }
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function adminTree(FactoryInterface $factory, array $options)
    {
        if (!isset($options['categoryClass'])) {
            throw new \Exception('Надо указать categoryClass в опциях');
        }

        $menu = $factory->createItem('categories');
        $this->addChildToAdminTree($menu, null, $options);

        return $menu;
    }

    /**
     * Рекурсивное построение дерева для админки.
     *
     * @param ItemInterface $menu
     * @param CategoryModel|null $parent
     * @param string $categoryClass
     * @return void
     */
    protected function addChildToAdminTree(ItemInterface $menu, $parent = null, $options)
    {
        $categories = $parent
            ? $parent->getChildren()
            : $this->container->get('doctrine')->getManager()->getRepository($options['categoryClass'])->findBy([
                'parent' => null,
                'structure' => $options['structure'],
            ]);

        /** @var CategoryModel $category */
        foreach ($categories as $category) {
            $uri = $this->container->get('router')->generate('smart_module.catalog_category_admin', [
                'id' => $category->getId(),
                'structure_id' => $category->getStructure()->getId()
            ]);
            $menu->addChild($category->getTitle(), ['uri' => $uri])
                ->setAttributes([
                    'class' => 'folder',
                    'id'    => 'category_id_' . $category->getId(),
                ]);

            /** @var ItemInterface $sub_menu */
            $sub_menu = $menu[$category->getTitle()];

            $this->addChildToAdminTree($sub_menu, $category, $options);
        }
    }
}
