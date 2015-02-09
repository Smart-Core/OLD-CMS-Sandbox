<?php

namespace SmartCore\Module\Blog\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\NodeInterface;

class DummyFactory implements FactoryInterface
{
    public function createItem($name, array $options = [])
    {
    }

    public function createFromNode(NodeInterface $node)
    {
    }

    public function createFromArray(array $data)
    {
    }
}
