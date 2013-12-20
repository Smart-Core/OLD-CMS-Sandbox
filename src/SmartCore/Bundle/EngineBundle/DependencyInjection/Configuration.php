<?php

namespace SmartCore\Bundle\EngineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('smart_core_engine');

        $rootNode
            ->children()
                ->scalarNode('storage')->defaultValue('database')->end() // cannotBeEmpty()->
                ->scalarNode('serialiser')->defaultValue('php')->info('Valid values: php, igbinary')->end() // @todo 
            ->end()
        ;

        return $treeBuilder;
    }
}