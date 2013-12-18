<?php

namespace SmartCore\Bundle\EngineBundle;

/**
 * @todo избавиться
 */
class Container
{
    static private $container;

    static public function get($name)
    {
        return self::$container->get($name);
    }

    static public function set($container)
    {
        self::$container = $container;
    }

    static public function getContainer()
    {
        return self::$container;
    }
}
