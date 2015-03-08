<?php

namespace SmartCore\Module\Unicat\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use Symfony\Component\DependencyInjection\ContainerAware;

class AdminMenu extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function configuration(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('unicat_configuration');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-tabs');

        /** @var UnicatConfiguration $configuration */
        $configuration = $options['configuration']->getName();

        // @todo кастомизация имени ссылки
        $item = $menu->addChild($options['configuration']->getTitle(), ['route' => 'unicat_admin.configuration', 'routeParameters' => ['configuration' => $configuration]]);
        //$item->setLinkAttribute('class', 'btn');

        $menu->addChild('Structures',   ['route' => 'unicat_admin.structures_index',    'routeParameters' => ['configuration' => $configuration]]);
        $menu->addChild('Attributes',   ['route' => 'unicat_admin.attributes_index',    'routeParameters' => ['configuration' => $configuration]]);
        $menu->addChild('Link names',   ['uri' => '#']);
        //$menu->addChild('Link names',   ['route' => 'unicat_admin.properties_index',    'routeParameters' => ['configuration' => $configuration]]);
        $menu->addChild('Settings',     ['route' => 'unicat_admin.configuration.settings', 'routeParameters' => ['configuration' => $configuration]]);

        return $menu;
    }
}
