<?php

namespace SmartCore\Bundle\CMSBundle\Router;

use SmartCore\Bundle\CMSBundle\Container;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class NodeRouter extends Router
{
    /**
     * В случае, если в пути маршрута есть паттерн {_folderPath}, то пробуем подставить его из $parameters или атрибута _route_params.
     *
     * Приходится использовать статический Container:: из-за того, что в Router он приватный :(
     *
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $container = Container::getContainer();

        $rootHash = md5($container->getParameter('secret'));

        $route = $this->getRouteCollection()->get($name);

        $path = $route ? $route->getPath() : null;

        if (false !== strpos($path, '{_folderPath}')) {
            if (isset($parameters['_folderPath'])) {
                // Удаление последнего слеша
                if (mb_substr($parameters['_folderPath'], - 1) == '/') {
                    $parameters['_folderPath'] = mb_substr($parameters['_folderPath'], 0, mb_strlen($parameters['_folderPath']) - 1);
                }

                // Удаление первого слеша
                if (mb_substr($parameters['_folderPath'], 0, 1) == '/') {
                    $parameters['_folderPath'] = mb_substr($parameters['_folderPath'], 1);
                }
            }

            $routeParams = $container->get('request')->attributes->get('_route_params', null);

            if (isset($routeParams['_folderPath']) and (!isset($parameters['_folderPath']) or empty($parameters['_folderPath']))) {
                $parameters['_folderPath'] = empty($routeParams['_folderPath']) ? $rootHash : $routeParams['_folderPath'];
            }
        }

        return str_replace($rootHash.'/', '', $this->getGenerator()->generate($name, $parameters, $referenceType));
    }
}
