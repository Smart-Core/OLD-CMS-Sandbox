<?php

namespace SmartCore\Bundle\CMSBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\RouteCollection;

class ModuleAdminRoutesLoader extends Loader implements LoaderInterface
{
    use ContainerAwareTrait;

    /**
     * @var bool
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

        $collection = new RouteCollection();

        foreach ($this->container->getParameter('smart_core_cms.modules') as $moduleName => $module) {
            $resource = $module['path'].'/Resources/config/routing_admin.yml';
            if (file_exists($resource)) {
                $importedRoutes = $this->import('@'.$moduleName.'Module/Resources/config/routing_admin.yml', 'yaml');
                $importedRoutes->addPrefix('/admin/module/'.$moduleName);

                $collection->addCollection($importedRoutes);
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
     * @return bool true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'module_admin' === $type;
    }
}
