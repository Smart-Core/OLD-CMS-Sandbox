<?php

namespace SmartCore\Module\Menu\Entity;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    /**
     * @param Group $group
     * @param Item $parent_item
     * @return Item[]
     */
    public function findByParent(Group $group, Item $parent_item = null)
    {
        return $this->findBy([
            'parent_item' => $parent_item,
            'group' => $group,
        ], ['position' => 'ASC']);
    }
}
