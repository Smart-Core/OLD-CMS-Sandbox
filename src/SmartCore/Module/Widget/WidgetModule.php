<?php

namespace SmartCore\Module\Widget;

use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Module\ModuleBundle;

class WidgetModule extends ModuleBundle
{
    /**
     * Действие при обновлении ноды.
     * @param Node $node
     *
    public function updateNode(Node $node)
    {
        $params = $node->getParams();

        $params['open_tag']  = str_replace("\n", "\r\n", $params['open_tag']);
        $params['close_tag'] = str_replace("\n", "\r\n", $params['close_tag']);

        $node->setParams($params);
    }
     */
}
