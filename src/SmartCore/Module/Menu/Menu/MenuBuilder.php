<?php

namespace SmartCore\Module\Menu\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use SmartCore\Module\Menu\Entity\Group;
use SmartCore\Module\Menu\Entity\Item;

class MenuBuilder extends ContainerAware
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Group
     */
    protected $group;

    /**
     * Режим администрирования.
     *
     * @var bool
     */
    protected $is_admin;

    /**
     * CSS стиль меню.
     *
     * @var string
     */
    protected $css_class;

    /**
     * Глубина вложенности.
     *
     * @var integer
     */
    protected $depth;

    /**
     * Построение полной структуры, включая ноды.
     *
     * @param FactoryInterface  $factory
     * @param array             $options
     *
     * @return ItemInterface
     */
    public function full(FactoryInterface $factory, array $options)
    {
        $this->processConfig($options);

        $menu = $factory->createItem('menu');

        if (empty($this->group)) {
            return $menu;
        }

        if (!empty($this->css_class)) {
            $menu->setChildrenAttribute('class', $this->css_class);
        }

        $this->addChild($menu);

        return $menu;
    }

    /**
     * Обработка конфига.
     *
     * @param array $options
     */
    protected function processConfig(array $options)
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');

        $defaul_options = $options + [
            'css_class' => null,
            'depth'     => null,
            'group'     => null,
            'is_admin'  => false,
        ];

        foreach ($defaul_options as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Рекурсивное построение дерева.
     *
     * @param ItemInterface $menu
     * @param Item|null     $parent_item
     */
    protected function addChild(ItemInterface $menu, Item $parent_item = null)
    {
        $items = (null == $parent_item)
            ? $this->em->getRepository('MenuModule:Item')->findByParent($this->group, null)
            : $parent_item->getChildren();

        /** @var Item $item */
        foreach ($items as $item) {
            if ($this->is_admin) {
                $uri = $this->container->get('router')->generate('smart_menu_admin_item', ['item_id' => $item->getId()]);
            } else {
                $uri = $item->getFolder()
                    ? $this->container->get('cms.folder')->getUri($item->getFolder()->getId())
                    : $item->getUrl();
            }

            $item_title = (string) $item;
            $item_title = isset($menu[$item_title]) ? $item_title . ' (' . $item->getId() . ')' : $item_title;

            if ($this->is_admin or $item->getIsActive()) {
                $new_item = $menu->addChild($item_title, ['uri' => $uri]);
                $new_item->setAttributes([
                    //'class' => 'my_item', // @todo аттрибуты для пунктов меню.
                    'title' => $item->getDescr(),
                ])->setExtras($item->getProperties());

                if ($this->is_admin and !$item->getIsActive()) {
                    $new_item->setAttribute('style', 'text-decoration: line-through;');
                }
            } else {
                continue;
            }

            $this->addChild($menu[$item_title], $item);
        }
    }
}
