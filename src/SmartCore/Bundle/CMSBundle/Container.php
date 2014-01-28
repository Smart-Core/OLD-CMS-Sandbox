<?php

namespace SmartCore\Bundle\CMSBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Container
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * @param $name
     * @return ContainerInterface
     */
    public static function get($name)
    {
        return self::$container->get($name);
    }

    /**
     * @param ContainerInterface $container
     */
    public static function set(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        return self::$container;
    }
}
