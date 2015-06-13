<?php

namespace SmartCore\Bundle\CMSBundle\Listener;

use SmartCore\Bundle\CMSBundle\Engine\EngineContext;
use SmartCore\Bundle\CMSBundle\Engine\EngineFolder;
use SmartCore\Bundle\CMSBundle\Engine\EngineModule;
use SmartCore\Bundle\CMSBundle\Engine\EngineNode;
use SmartCore\Bundle\CMSBundle\Twig\Loader\FilesystemLoader;
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

    /** @var EngineModule */
    protected $engineModule;

    /** @var EngineNode */
    protected $engineNodeManager;

    /** @var \SmartCore\Bundle\CMSBundle\Twig\Loader\FilesystemLoader  */
    protected $twigLoader;

    /**
     * @param EngineContext $engineContext
     * @param EngineFolder $engineFolder
     * @param EngineModule $engineModule
     * @param EngineNode $engineNodeManager
     * @param FilesystemLoader $twigLoader
     */
    public function __construct(
        EngineContext $engineContext,
        EngineFolder $engineFolder,
        EngineModule $engineModule,
        EngineNode $engineNodeManager,
        FilesystemLoader $twigLoader
    ) {
        $this->engineContext      = $engineContext;
        $this->engineFolder       = $engineFolder;
        $this->engineModule       = $engineModule;
        $this->engineNodeManager  = $engineNodeManager;
        $this->twigLoader         = $twigLoader;
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

            if ($this->engineModule->has($node->getModule())) {
                $isValidRequiredParams = true;
                foreach ($this->engineModule->get($node->getModule())->getRequiredParams() as $param) {
                    if (null === $node->getParam($param)) {
                        $isValidRequiredParams = false;
                    }
                }

                if (!$isValidRequiredParams) {
                    $controller[0] = new \SmartCore\Bundle\CMSBundle\Controller\EngineController();
                    $controller[1] = 'moduleNotConfiguredAction';
                    $event->setController($controller);

                    return;
                }
            }

            // @todo сделать поддержку кириллических путей.
            $folderPath = substr(str_replace($request->getBaseUrl(), '', $this->engineFolder->getUri($node)), 1);

            if (false !== strrpos($folderPath, '/', strlen($folderPath) - 1)) {
                $folderPath = substr($folderPath, 0, strlen($folderPath) - 1);
            }

            $routeParams = $node->getControllerParams();
            $routeParams['_folderPath'] = $folderPath;

            $request->attributes->set('_route_params', $routeParams);

            if (method_exists($controller[0], 'setNode')) {
                $controller[0]->setNode($node);
                $this->twigLoader->setModuleTheme($node);
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
        $this->twigLoader->setModuleTheme(null);
    }
}
