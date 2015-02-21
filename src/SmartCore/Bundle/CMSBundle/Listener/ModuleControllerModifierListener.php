<?php

namespace SmartCore\Bundle\CMSBundle\Listener;

use SmartCore\Bundle\CMSBundle\Engine\EngineContext;
use SmartCore\Bundle\CMSBundle\Engine\EngineFolder;
use SmartCore\Bundle\CMSBundle\Engine\EngineNode;
use SmartCore\Bundle\CMSBundle\Locator\ModuleThemeLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ModuleControllerModifierListener
{
    /** @var EngineContext */
    protected $engineContext;

    /** @var EngineFolder */
    protected $engineFolder;

    /** @var EngineNode */
    protected $engineNodeManager;

    /** @var ModuleThemeLocator */
    protected $moduleThemeLocator;

    /**
     * @param EngineContext $engineContext
     * @param EngineFolder $engineFolder
     * @param EngineNode $engineNodeManager
     * @param ModuleThemeLocator $moduleThemeLocator
     */
    public function __construct(EngineContext $engineContext, EngineFolder $engineFolder, EngineNode $engineNodeManager, ModuleThemeLocator $moduleThemeLocator)
    {
        $this->engineContext      = $engineContext;
        $this->engineFolder       = $engineFolder;
        $this->engineNodeManager  = $engineNodeManager;
        $this->moduleThemeLocator = $moduleThemeLocator;
    }

    public function onView(GetResponseForControllerResultEvent $event)
    {
        $response = new Response();
        $response->setContent($event->getControllerResult());

        $event->setResponse($response);
    }

    public function onController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $request = $event->getRequest();
        if ($request->attributes->has('_node')) {
            /** @var $node \SmartCore\Bundle\CMSBundle\Entity\Node */
            $node = $request->attributes->get('_node');

            // @todo сделать поддержку кириллических путей.
            $basePath = substr(str_replace($request->getBaseUrl(), '', $this->engineFolder->getUri($node)), 1);

            if (false !== strrpos($basePath, '/', strlen($basePath) - 1)) {
                $basePath = substr($basePath, 0, strlen($basePath) - 1);
            }

            //$routeParams = $request->attributes->get('_route_params', null);

            //if (isset($routeParams['slug']) and 0 === strpos($routeParams['slug'], $basePath, 0)) {
            $routeParams = $node->getControllerParams();
            $routeParams['_basePath'] = $basePath;

            $request->attributes->set('_route_params', $routeParams);
            //}

            if (method_exists($controller[0], 'setNode')) {
                $controller[0]->setNode($node);
                $this->moduleThemeLocator->setModuleTheme($node->getTemplate());
            }

            $this->engineContext->setCurrentNodeId($node->getId());
            $request->attributes->remove('_node');
        }
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::SUB_REQUEST === $event->getRequestType()) {
            $controller = explode(':', $event->getRequest()->attributes->get('_controller'));

            if (is_numeric($controller[0])) {
                $node = $this->engineNodeManager->get($controller[0]);

                $controllerName = isset($controller[1]) ? $controller[1] : null;
                $actionName = isset($controller[2]) ? $controller[2] : 'index';

                foreach ($node->getController($controllerName, $actionName) as $key => $value) {
                    $event->getRequest()->attributes->set($key, $value);
                }

                $event->getRequest()->attributes->set('_node', $node);
            }
        }
    }

    public function onResponse(FilterResponseEvent $event)
    {
        $this->engineContext->setCurrentNodeId(null);
        $this->moduleThemeLocator->setModuleTheme(null);
    }
}
