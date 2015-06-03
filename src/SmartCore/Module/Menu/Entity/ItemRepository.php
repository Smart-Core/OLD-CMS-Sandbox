<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    /**
     * @param Menu      $menu
     * @param Item|null $parent_item
     *
     * @return Item[]
     */
    public function findByParent(Menu $menu, Item $parent_item = null)
    {
        return $this->findBy([
            'parent_item' => $parent_item,
            'menu'        => $menu,
        ], ['position' => 'ASC']);
    }
}
