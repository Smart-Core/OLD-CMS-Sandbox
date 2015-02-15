<?php

namespace SmartCore\Bundle\SettingsBundle;

use SmartCore\Bundle\SettingsBundle\DependencyInjection\Compiler\SettingsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartCoreSettingsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SettingsPass(), PassConfig::TYPE_AFTER_REMOVING);
    }
}
