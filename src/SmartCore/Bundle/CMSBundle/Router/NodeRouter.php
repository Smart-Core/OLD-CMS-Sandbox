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
     * Сейчас приходится использовать статический Container:: из-за того, что в Router он приватный :(
     *
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $rootHash = md5(Container::getParameter('secret'));

        // @todo пока что так не подставляется _basePath для админских маршрутов.
        if (false === stripos($name, 'admin')) {
            /** @var Request $request */
            $request = Container::get('request');

            $routeParams = $request->attributes->get('_route_params', null);

            if (isset($routeParams['_basePath']) and (!isset($parameters['_basePath']) or empty($parameters['_basePath']))) {
                $parameters['_basePath'] = empty($routeParams['_basePath']) ? $rootHash : $routeParams['_basePath'];
            }
        }

        return str_replace($rootHash . '/' , '', $this->getGenerator()->generate($name, $parameters, $referenceType));
    }
}
