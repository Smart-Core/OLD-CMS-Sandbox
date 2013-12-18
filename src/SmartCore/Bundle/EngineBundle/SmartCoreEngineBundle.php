<?php

namespace SmartCore\Bundle\EngineBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use SmartCore\Bundle\EngineBundle\DependencyInjection\Compiler\TemplateResourcesPass;

class SmartCoreEngineBundle extends Bundle
{
    protected $modules_cache = [];
    protected $modules = [];
    
    public function boot()
    {
        Container::set($this->container);
        
        if ($this->container->get('kernel')->getEnvironment() == 'prod' and $this->container->has('db.logger')) {
            $this->container->get('database_connection')->getConfiguration()->setSQLLogger($this->container->get('db.logger'));
        }
    }

    /*
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TemplateResourcesPass());
    }
    */

}
