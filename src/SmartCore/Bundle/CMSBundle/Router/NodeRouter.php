<?php

namespace SmartCore\Bundle\CMSBundle\Router;

use SmartCore\Bundle\CMSBundle\Container;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class NodeRouter extends Router
{
    /**
     * В случае если в текущем запросе есть аттрибут '_basePath', то считается, что выполнен
     * запрос в контексте ноды и нужно добавить '_basePath' в рараметры маршрута.
     *
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        /** @var Request $request */
        $request = Container::get('request');

        $routeParams = $request->attributes->get('_route_params', null);

        if (isset($routeParams['_basePath']) and !isset($parameters['_basePath'])) {
            $parameters['_basePath'] = $routeParams['_basePath'];
        }

        return $this->getGenerator()->generate($name, $parameters, $referenceType);
    }
}
