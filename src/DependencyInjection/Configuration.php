<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dynamic_db');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->integerNode('port')->defaultValue(3306)->end()
                ->scalarNode('user')->defaultValue('root')->end()
                ->scalarNode('password')->defaultValue('')->end()
                ->scalarNode('database')->defaultNull()->end()
                ->scalarNode('charset')->defaultValue('utf8mb4')->end()
            ->end();

        return $treeBuilder;
    }
}
