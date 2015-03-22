<?php

namespace SmartCore\Bundle\CMSBundle;

use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\FormPass;
use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\ModulesRoutingResolverPass;
use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\RemoveTagcacheActionCacheListenerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CMSBundle extends Bundle
{
    public function boot()
    {
        Container::setContainer($this->container);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ModulesRoutingResolverPass());
        $container->addCompilerPass(new FormPass());
        $container->addCompilerPass(new RemoveTagcacheActionCacheListenerPass()); //, PassConfig::TYPE_AFTER_REMOVING);
    }
}
