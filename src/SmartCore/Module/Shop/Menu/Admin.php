<?php

namespace SmartCore\Module\Shop\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Admin extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function shipping(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('smart_shop_admin_shipping');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-pills');

        $menu->addChild('Активные',    ['route' => 'smart_module.shop.admin.shipping.active']);
        $menu->addChild('Завершенные', ['route' => 'smart_module.shop.admin.shipping.finished']);
        $menu->addChild('Настройки',   ['route' => 'smart_module.shop.admin.shipping.settings']);

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function orders(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('smart_shop_admin_orders');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-pills');

        $menu->addChild('Все',          ['route' => 'smart_module.shop.admin.orders.all']);
        $menu->addChild('Aктивные',     ['route' => 'smart_module.shop.admin.orders.active']);
        $menu->addChild('Доставляются', ['route' => 'smart_module.shop.admin.orders.shipping']);
        $menu->addChild('Выполненные',  ['route' => 'smart_module.shop.admin.orders.completed']);
        $menu->addChild('Отменённые',   ['route' => 'smart_module.shop.admin.orders.cancelled']);

        return $menu;
    }
}
