<?php

namespace SmartCore\Module\Shop\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Client extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function orders(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('smart_shop_client_orders');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-pills');

        $menu->addChild('Все заказы',   ['route' => 'smart_module.shop.orders.all']);
        $menu->addChild('Aктивные',     ['route' => 'smart_module.shop.orders.active']);
        $menu->addChild('Выполненные',  ['route' => 'smart_module.shop.orders.completed']);
        $menu->addChild('Отменённые',   ['route' => 'smart_module.shop.orders.canceled']);

        return $menu;
    }
}
