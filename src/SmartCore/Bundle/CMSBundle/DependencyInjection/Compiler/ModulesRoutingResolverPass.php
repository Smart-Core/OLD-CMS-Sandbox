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

                // Обработка routing.yml
                $reflector = new \ReflectionClass($moduleClass);
                $routingConfig = dirname($reflector->getFileName()) . '/Resources/config/routing.yml';

                if (file_exists($routingConfig)) {
                    $definition = new Definition(
                        'Symfony\\Component\\Routing\\Router', [
                            new Reference('routing.loader'),
                            $routingConfig, [
                                'cache_dir' => $container->getParameter('kernel.cache_dir') . '/smart_core_cms',
                                'debug'     => $container->getParameter('kernel.debug'),
                                'matcher_cache_class'   => 'CMSModule' . $moduleName . 'UrlMatcher',
                                'generator_cache_class' => 'CMSModule' . $moduleName . 'UrlGenerator',
                            ]
                        ]
                    );
                    $definition->addTag('cms_router_module');
                    $container->setDefinition('cms.router_module.' . $moduleName, $definition);
                }

                // Обработка routing_admin.yml
                $routingConfig = dirname($reflector->getFileName()) . '/Resources/config/routing_admin.yml';
                if (file_exists($routingConfig)) {
                    $definition = new Definition(
                        'Symfony\\Component\\Routing\\Router', [
                            new Reference('routing.loader'),
                            $routingConfig, [
                                'cache_dir' => $container->getParameter('kernel.cache_dir') . '/smart_core_cms',
                                'debug'     => $container->getParameter('kernel.debug'),
                                'matcher_cache_class'   => 'CMSModule' . $moduleName . 'AdminUrlMatcher',
                                'generator_cache_class' => 'CMSModule' . $moduleName . 'AdimnUrlGenerator',
                            ]
                        ]
                    );
                    $definition->addTag('cms_router_module_admin');

                    // Сохранение списка сервисов маршрутов, чтобы можно было быстро перебрать их на название роутов.
                    $cms_router_module_admin = $container->hasParameter('cms_router_module_admin')
                        ? $container->getParameter('cms_router_module_admin')
                        : [];

                    $serviceName = 'cms.router_module.' . strtolower($moduleName) . '.admin';

                    $cms_router_module_admin[$moduleName] = $serviceName;
                    $container->setParameter('cms_router_module_admin', $cms_router_module_admin);

                    $container->setDefinition($serviceName, $definition);
                }
            }
        }
    }
}
