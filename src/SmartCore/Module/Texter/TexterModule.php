<?php

namespace SmartCore\Module\Texter;

use SmartCore\Bundle\CMSBundle\Module\Bundle;
use SmartCore\Module\Texter\Entity\Item;

class TexterModule extends Bundle
{
    /**
     * Действие при создании ноды.
     * @param \SmartCore\Bundle\CMSBundle\Entity\Node $node
     */
    public function createNode($node)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $item = new Item();
        $item->setUserId($this->container->get('security.context')->getToken()->getUser()->getId());

        $em->persist($item);
        $em->flush();

        $node->setParams([
            'text_item_id' => $item->getId()
        ]);
    }

    public function hasAdmin()
    {
        return true;
    }
}
