<?php

namespace SmartCore\Bundle\CMSBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ModuleControllerModifierListener
{
    /**
     * @var \SmartCore\Bundle\CMSBundle\Engine\EngineContext
     */
    protected $engineContext;

    /**
     * @var \SmartCore\Bundle\CMSBundle\Engine\EngineNode
     */
    protected $engineNodeManager;

    /**
     * @param \SmartCore\Bundle\CMSBundle\Engine\EngineContext $engineContext
     * @param \SmartCore\Bundle\CMSBundle\Engine\EngineNode $engineNodeManager
     */
    public function __construct($engineContext, $engineNodeManager)
    {
        $this->engineContext = $engineContext;
        $this->engineNodeManager = $engineNodeManager;
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

        if ($event->getRequest()->attributes->has('_node')) {
            /** @var $node \SmartCore\Bundle\CMSBundle\Entity\Node */
            $node = $event->getRequest()->attributes->get('_node');
            if (method_exists($controller[0], 'setNode')) {
                $controller[0]->setNode($node);
            }
            $this->engineContext->setCurrentNodeId($node->getId());
            $event->getRequest()->attributes->remove('_node');
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
    }
}
