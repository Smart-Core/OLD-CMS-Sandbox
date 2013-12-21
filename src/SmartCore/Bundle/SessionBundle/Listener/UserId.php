<?php

namespace SmartCore\Bundle\SessionBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class UserId
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function updateSessionUserId(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()
            and $this->container->has('security.context')
            and $this->container->get('security.context')->getToken() instanceof TokenInterface
            and method_exists($this->container->get('security.context')->getToken()->getUser(), 'getId')
        ) {
            $user_id = $this->container->get('security.context')->getToken()->getUser()->getId();
            $this->container->get('smart_core_session.handler')->setUserId($user_id);
        }
    }
}
