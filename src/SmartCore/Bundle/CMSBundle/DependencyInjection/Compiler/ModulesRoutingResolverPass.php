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
        foreach ($container->getParameter('smart_core_cms.modules') as $moduleName => $module) {

            // Обработка routing.yml
            $routingConfig = $module['path'].'/Resources/config/routing.yml';

            if (file_exists($routingConfig)) {
                $definition = new Definition(
                    'Symfony\\Component\\Routing\\Router', [
                        new Reference('routing.loader'),
                        $routingConfig, [
                            'cache_dir' => $container->getParameter('kernel.cache_dir').'/smart_core_cms',
                            'debug'     => $container->getParameter('kernel.debug'),
                            'matcher_cache_class'   => 'CMSModule'.$moduleName.'UrlMatcher',
                            'generator_cache_class' => 'CMSModule'.$moduleName.'UrlGenerator',
                        ],
                    ]
                );
                $definition->addTag('cms_router_module');
                $container->setDefinition('cms.router_module.'.$moduleName, $definition);
            }

            // Обработка routing_admin.yml
            $routingConfig = $module['path'].'/Resources/config/routing_admin.yml';
            if (file_exists($routingConfig)) {
                $definition = new Definition(
                    'Symfony\\Component\\Routing\\Router', [
                        new Reference('routing.loader'),
                        $routingConfig, [
                            'cache_dir' => $container->getParameter('kernel.cache_dir').'/smart_core_cms',
                            'debug'     => $container->getParameter('kernel.debug'),
                            'matcher_cache_class'   => 'CMSModule'.$moduleName.'AdminUrlMatcher',
                            'generator_cache_class' => 'CMSModule'.$moduleName.'AdimnUrlGenerator',
                        ],
                    ]
                );
                $definition->addTag('cms_router_module_admin');

                // Сохранение списка сервисов маршрутов, чтобы можно было быстро перебрать их на название роутов.
                $cms_router_module_admin = $container->hasParameter('cms_router_module_admin')
                    ? $container->getParameter('cms_router_module_admin')
                    : [];

                $serviceName = 'cms.router_module.'.strtolower($moduleName).'.admin';

                $cms_router_module_admin[$moduleName] = $serviceName;
                $container->setParameter('cms_router_module_admin', $cms_router_module_admin);

                $container->setDefinition($serviceName, $definition);
            }

            // Обработка routing_api.yml
            $routingConfig = $module['path'].'/Resources/config/routing_api.yml';

            if (file_exists($routingConfig)) {
                $definition = new Definition(
                    'Symfony\\Component\\Routing\\Router', [
                        new Reference('routing.loader'),
                        $routingConfig, [
                            'cache_dir' => $container->getParameter('kernel.cache_dir').'/smart_core_cms',
                            'debug'     => $container->getParameter('kernel.debug'),
                            'matcher_cache_class'   => 'CMSModule'.$moduleName.'ApiUrlMatcher',
                            'generator_cache_class' => 'CMSModule'.$moduleName.'ApiUrlGenerator',
                        ],
                    ]
                );
                $definition->addTag('cms_router_module_api');
                $container->setDefinition('cms.router_module_api.'.$moduleName, $definition);
            }
        }
    }
}
