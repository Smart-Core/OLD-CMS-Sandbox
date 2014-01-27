<?php

namespace SmartCore\Module\Texter;

use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Module\Bundle;
use SmartCore\Module\Texter\Entity\Item;

class TexterModule extends Bundle
{
    /**
     * Действие при создании ноды.
     * @param Node $node
     */
    public function createNode(Node $node)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $item = new Item();
        $item->setUserId($this->container->get('security.context')->getToken()->getUser()->getId());

        $em->persist($item);
        $em->flush($item);

        $node->setParams([
            'text_item_id' => $item->getId(),
            'editor' => true,
        ]);
    }

    /**
     * Действие при обновлении ноды.
     * @param Node $node
     */
    public function updateNode(Node $node)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        /** @var Item $item */
        $item = $em->find('TexterModule:Item', $node->getParam('text_item_id'));

        if ($item) {
            $item->setEditor((int) $node->getParam('editor'));
            $em->persist($item);
            $em->flush($item);
        }
    }
}
