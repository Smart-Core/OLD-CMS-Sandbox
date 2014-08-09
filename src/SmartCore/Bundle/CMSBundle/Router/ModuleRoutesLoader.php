<?php

namespace SmartCore\Bundle\CMSBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\RouteCollection;

class ModuleRoutesLoader extends Loader implements LoaderInterface
{
    use ContainerAwareTrait;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @return RouteCollection
     *
     * @throws \RuntimeException Loader is added twice
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $modulesIni = $this->container->getParameter('kernel.root_dir') . '/usr/modules.ini';

        if (!file_exists($modulesIni)) {
            return null;
        }

        $collection = new RouteCollection();

        foreach (parse_ini_file($modulesIni) as $moduleName => $moduleClass) {
            if (class_exists($moduleClass)) {
                $reflector = new \ReflectionClass($moduleClass);

                $resource = dirname($reflector->getFileName()) . '/Resources/config/routing.yml';
                if (file_exists($resource)) {
                    /** @var \Symfony\Component\Routing\RouteCollection $importedRoutes */
                    $importedRoutes = $this->import('@' . $moduleName . 'Module/Resources/config/routing.yml', 'yaml');
                    $importedRoutes->addPrefix(
                        '/{_basePath}/',
                        ['_basePath' => ''],
                        ['_basePath' => '.*']
                    );

                    $collection->addCollection($importedRoutes);
                }
            }
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'installed_modules' === $type;
    }
}
