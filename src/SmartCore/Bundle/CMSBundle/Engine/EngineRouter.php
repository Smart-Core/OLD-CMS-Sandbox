<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EngineRouter extends ContainerAware
{
    /**
     * @param string $module
     * @param string $path
     * @return array
     */
    public function matchModuleAdmin($module, $path)
    {
        return $this->container->get('cms.router_module.' . $module . '.admin')->match($path);
    }
}
