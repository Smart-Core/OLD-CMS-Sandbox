<?php

namespace SmartCore\Bundle\CMSBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
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

        if ($event->getRequest()->attributes->has('_eip')) {
            $controller[0]->setEip($event->getRequest()->attributes->get('_eip'));

            $event->getRequest()->attributes->remove('_eip');
        }

        if ($event->getRequest()->attributes->has('_node')) {
            /** @var $node \SmartCore\Bundle\CMSBundle\Entity\Node */
            $node = $event->getRequest()->attributes->get('_node');
            $controller[0]->setNode($node);
            $this->engineContext->setCurrentNodeId($node->getId());
            $event->getRequest()->attributes->remove('_node');
        }
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::SUB_REQUEST === $event->getRequestType()) {
            $controller = explode(':', $event->getRequest()->attributes->get('_controller'));

            if (is_numeric($controller[0])) {
                /** @var $node \SmartCore\Bundle\CMSBundle\Entity\Node */
                $node = $this->engineNodeManager->get($controller[0]);

                foreach ($node->getController() as $key => $value) {
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
