<?php

namespace SmartCore\Bundle\CMSBundle\Router;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RequestContext;

class NodeRouter extends Router
{
    /** @var ContainerInterface */
    protected $mycontainer;

    /** @var string */
    protected $rootHash = 'kksdg7724tkshdfvI6734khvsdfKHvdf74';

    /**
     * Приходится переопределять конструктор из-за того, что в Router container приватный :(
     *
     * Constructor.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     * @param mixed              $resource  The main resource to load
     * @param array              $options   An array of options
     * @param RequestContext     $context   The context
     */
    public function __construct(ContainerInterface $container, $resource, array $options = array(), RequestContext $context = null)
    {
        parent::__construct($container, $resource, $options, $context);
        $this->mycontainer = $container;
    }

    /**
     * В случае, если в пути маршрута есть паттерн {_folderPath}, то пробуем подставить его из $parameters или атрибута _route_params.
     *
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        // Метод getDeclaredRouteData() генерируется через SmartCore\Bundle\CMSBundle\Router\PhpGeneratorDumper
        $declaredRouteData = $this->getGenerator()->getDeclaredRouteData($name);

        if (isset($declaredRouteData[0][0]) and in_array('_folderPath', $declaredRouteData[0])) {
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

            $routeParams = $this->mycontainer->get('request')->attributes->get('_route_params', null);

            if (isset($routeParams['_folderPath']) and (!isset($parameters['_folderPath']) or empty($parameters['_folderPath']))) {
                $parameters['_folderPath'] = empty($routeParams['_folderPath']) ? $this->rootHash : $routeParams['_folderPath'];
            }
        }

        return str_replace($this->rootHash.'/', '', $this->getGenerator()->generate($name, $parameters, $referenceType));
    }
}
