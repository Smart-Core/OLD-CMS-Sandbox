<?php

namespace SmartCore\Bundle\EngineBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

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
}
