<?php

namespace SmartCore\Module\Unicat;

use SmartCore\Bundle\CMSBundle\Module\ModuleBundleTrait;
use SmartCore\Module\Unicat\DependencyInjection\Compiler\FormPass;
use SmartCore\Module\Unicat\DependencyInjection\UnicatExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UnicatModule extends Bundle
{
    use ModuleBundleTrait;

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormPass());
    }

    public function getContainerExtension()
    {
        return new UnicatExtension();
    }
}
