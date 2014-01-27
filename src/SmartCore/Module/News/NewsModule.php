<?php

namespace SmartCore\Module\News;

use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Module\Bundle;

class NewsModule extends Bundle
{
    /**
     * Действие при создании ноды.
     * @param Node $node
     */
    public function createNode(Node $node)
    {
        $node->setParams([
            'items_per_page' => 10,
        ]);
    }
}
