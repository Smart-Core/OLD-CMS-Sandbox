<?php

namespace SmartCore\Bundle\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $rootNode = $treeBuilder->root('smart_sitemap');

        $rootNode
            ->children()
                ->scalarNode('base_url')->isRequired()->cannotBeEmpty()->end()
                ->integerNode('limit')->defaultValue(50000)->end()
                ->scalarNode('target')->defaultNull()->end()
                ->arrayNode('ignores')
                    ->prototype('scalar')->end()
                    ->defaultValue([])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
