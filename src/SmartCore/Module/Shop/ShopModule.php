<?php

namespace SmartCore\Module\Shop;

use Knp\Menu\MenuItem;
use SmartCore\Bundle\CMSBundle\Module\ModuleBundle;

class ShopModule extends ModuleBundle
{
    /**
     * @param MenuItem $menu
     * @param array $extras
     *
     * @return MenuItem
     */
    public function buildAdminMenu(MenuItem $menu, array $extras = ['beforeCode' => '<i class="fa fa-angle-right"></i>'])
    {
        if ($this->hasAdmin()) {
            $extras = [
                'afterCode'  => '<i class="fa fa-angle-left pull-right"></i>',
                'beforeCode' => '<i class="fa fa-money"></i>',
            ];

            $submenu = $menu->addChild($this->getShortName(), ['uri' => $this->container->get('router')->generate('cms_admin_index').$this->getShortName().'/'])
                ->setAttribute('class', 'treeview')
                ->setExtras($extras)
            ;

            $submenu->setChildrenAttribute('class', 'treeview-menu');

            $submenu->addChild('Заказы', [
                'route' => 'smart_module.shop.admin',
                'routeParameters' => ['page' => 'orders'],
            ])->setExtras(['beforeCode' => '<i class="fa fa-credit-card"></i>']);

            $submenu->addChild('Корзины', [
                'route' => 'smart_module.shop.admin',
                'routeParameters' => ['page' => 'baskets'],
            ])->setExtras(['beforeCode' => '<i class="fa fa-cart-arrow-down"></i>']);

            $submenu->addChild('Доставка', [
                'route' => 'smart_module.shop.admin.shipping',
            ])->setExtras(['beforeCode' => '<i class="fa fa-truck"></i>']);

            $submenu->addChild('Оплата', [
                'route' => 'smart_module.shop.admin.payment',
            ])->setExtras(['beforeCode' => '<i class="fa fa-usd"></i>']);

            /*
            $submenu->addChild('Settings', [
                'route' => 'smart_module.shop.admin.settings',
            ])->setExtras(['beforeCode' => '<i class="fa fa-cogs"></i>']);
            */

            $submenu->addChild('Catalog', [
                'route' => 'unicat_admin.configuration',
                'routeParameters' => ['configuration' => $this->container->get('settings')->get('shopmodule', 'catalog')],
            ])->setExtras(['beforeCode' => '<i class="fa fa-angle-right"></i>']);
        }

        return $menu;
    }
}
