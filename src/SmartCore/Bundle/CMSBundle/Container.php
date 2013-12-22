<?php

namespace SmartCore\Bundle\CMSBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Container
{
    /**
     * @var ContainerInterface
     */
    static private $container;

    /**
     * @param $name
     * @return ContainerInterface
     */
    static public function get($name)
    {
        return self::$container->get($name);
    }

    /**
     * @param ContainerInterface $container
     */
    static public function set(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * @return ContainerInterface
     */
    static public function getContainer()
    {
        return self::$container;
    }
}
