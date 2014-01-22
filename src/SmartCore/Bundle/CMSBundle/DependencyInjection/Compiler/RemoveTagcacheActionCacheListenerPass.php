<?php

namespace SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RemoveTagcacheActionCacheListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->removeDefinition('action_cache_listener_service');
    }
}
