<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EngineRouter extends ContainerAware
{
    /**
     * Generates a URL to from the given parameters.
     *
     * @param string         $route         The name of the route
     * @param mixed          $parameters    An array of parameters
     * @param Boolean|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    public function generateModuleAdminUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $adminRoutesServices = $this->container->getParameter('cms_router_module_admin');

        foreach ($adminRoutesServices as $moduleName => $adminRoutesServiceName) {
            /** @var \Symfony\Component\Routing\Router $adminRoutesService */
            $adminRoutesService = $this->container->get($adminRoutesServiceName);
            if ($adminRoutesService->getRouteCollection()->get($route)) {

                return $this->container->get('router')->generate('cms_admin_module_manage', ['module' => $moduleName], $referenceType) .
                    $adminRoutesService->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
            }
        }

        throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $route));
    }

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
