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
     * @param  string $name
     * @return ContainerInterface
     */
    public static function get($name)
    {
        return self::$container->get($name);
    }

    /**
     * @param  string $name
     * @return ContainerInterface
     */
    public static function has($name)
    {
        return self::$container->has($name);
    }

    /**
     * @param ContainerInterface $container
     */
    public static function setContainer(ContainerInterface $container)
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

    /**
     * @param  string $name
     * @return mixed
     */
    public static function getParameter($name)
    {
        return self::$container->getParameter($name);
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public static function hasParameter($name)
    {
        return self::$container->hasParameter($name);
    }
}
