<?php

namespace SmartCore\Bundle\CMSBundle;

use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\FormPass;
use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\ModulesRoutingResolverPass;
use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\RemoveTagcacheActionCacheListenerPass;
use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\SettingsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler\TemplateResourcesPass;

class CMSBundle extends Bundle
{
    public function boot()
    {
        Container::setContainer($this->container);

        if ($this->container->get('kernel')->getEnvironment() == 'prod' and $this->container->has('db.logger')) {
            $this->container->get('database_connection')->getConfiguration()->setSQLLogger($this->container->get('db.logger'));
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        //$container->addCompilerPass(new TemplateResourcesPass());
        $container->addCompilerPass(new ModulesRoutingResolverPass());
        $container->addCompilerPass(new FormPass());
        $container->addCompilerPass(new RemoveTagcacheActionCacheListenerPass()); //, PassConfig::TYPE_AFTER_REMOVING);
    }
}
