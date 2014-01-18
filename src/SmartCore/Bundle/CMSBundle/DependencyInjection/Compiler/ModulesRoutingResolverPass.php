<?php

namespace SmartCore\Bundle\CMSBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Обход всех модулей и создание сервисов роутингов для каждого.
 */
class ModulesRoutingResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $modulesIni = $container->getParameter('kernel.root_dir') . '/usr/modules.ini';

        if (!file_exists($modulesIni)) {
            return null;
        }

        foreach (parse_ini_file($modulesIni) as $moduleName => $moduleClass) {
            if (class_exists($moduleClass)) {
                $reflector = new \ReflectionClass($moduleClass);
                $routingConfig = dirname($reflector->getFileName()) . '/Resources/config/routing.yml';
                if (file_exists($routingConfig)) {
                    $container->setDefinition('cms.router_module.' . $moduleName, new Definition(
                        'Symfony\\Component\\Routing\\Router', [
                            new Reference('routing.loader'),
                            $routingConfig, [
                                'cache_dir' => $container->getParameter('kernel.cache_dir') . '/smart_core_cms',
                                'debug'     => $container->getParameter('kernel.debug'),
                                'matcher_cache_class'   => 'CMSModule' . $moduleName . 'UrlMatcher',
                                'generator_cache_class' => 'CMSModule' . $moduleName . 'UrlGenerator',
                            ]
                        ]
                    ));
                }
            }
        }
    }
}
