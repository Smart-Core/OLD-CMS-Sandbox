<?php

namespace SmartCore\Bundle\EngineBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use SmartCore\Bundle\EngineBundle\Entity\Folder;

class AdminMenu extends ContainerAware
{
    public function main(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('admin_main');

        if (isset($options['class'])) {
            $menu->setChildrenAttribute('class', $options['class']);
        } else {
            $menu->setChildrenAttribute('class', 'nav');
        }

        $menu->addChild('Structure',     ['route' => 'cmf_admin_structure']);
        $menu->addChild('Appearance',    ['route' => 'cmf_admin_appearance']);
        $menu->addChild('Users',         ['route' => 'cmf_admin_users']);
        $menu->addChild('Modules',       ['route' => 'cmf_admin_module']);
        $menu->addChild('Configuration', ['route' => 'cmf_admin_config']);
        $menu->addChild('Reports',       ['route' => 'cmf_admin_reports']);
        $menu->addChild('Help',          ['route' => 'cmf_admin_help']);

        return $menu;
    }

    /**
     * Меню управления стуктурой (папки и блоки).
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function structure(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('admin_structure');

        if (isset($options['class'])) {
            $menu->setChildrenAttribute('class', $options['class']);
        } else {
            $menu->setChildrenAttribute('class', 'nav nav-pills');
        }

        $menu->addChild('Create folder',  ['route' => 'cmf_admin_structure_folder_create']);
        $menu->addChild('Connect module', ['route' => 'cmf_admin_structure_node_create']);
        $menu->addChild('Blocks',         ['route' => 'cmf_admin_structure_block']);

        return $menu;
    }

    /**
     * Построение полной структуры, включая ноды.
     *
     * @param FactoryInterface  $factory
     * @param array             $options
     *
     * @return ItemInterface
     */
    public function structureTree(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('full_structure');
        $menu->setChildrenAttributes([
            'class' => 'filetree',
            'id' => 'browser',
        ]);

        $this->addChild($menu);

        return $menu;
    }

    /**
     * Рекурсивное построение дерева.
     *
     * @param ItemInterface $menu
     * @param Folder        $parent_folder
     */
    protected function addChild(ItemInterface $menu, Folder $parent_folder = null)
    {
        if (null == $parent_folder) {
            $folders = $this->container->get('engine.folder')->findByParent(null);
        } else {
            $folders = $parent_folder->getChildren();
        }

        /** @var $folder Folder */
        foreach ($folders as $folder) {
            $uri = $this->container->get('router')->generate('cmf_admin_structure_folder', ['id' => $folder->getId()]);
            $menu->addChild($folder->getTitle(), ['uri' => $uri])->setAttributes([
                'class' => 'folder',
                'title' => $folder->getDescr(),
                'id' => 'folder_id_' . $folder->getId(),
            ]);

            /** @var $sub_menu ItemInterface */
            $sub_menu = $menu[$folder->getTitle()];

            $this->addChild($sub_menu, $folder);

            /** @var $node \SmartCore\Bundle\EngineBundle\Entity\Node */
            foreach ($folder->getNodes() as $node) {
                $uri = $this->container->get('router')->generate('cmf_admin_structure_node_properties', ['id' => $node->getId()]);
                $sub_menu->addChild($node->getDescr() . ' (' . $node->getModule() . ':' . $node->getId() . ')', ['uri' => $uri])->setAttributes([
                    'title' => $node->getDescr(),
                    'id' => 'node_id_' . $node->getId(),
                ]);
            }
        }
    }
}
