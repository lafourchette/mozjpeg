<?php

namespace LaFourchette\Mozjpeg\Plugin\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;
        $rootNode = $treeBuilder->root('la_fourchette_mozjpeg');

        $rootNode
            ->children()
                ->arrayNode('jpegtran')->isRequired()
                ->children()
                    ->scalarNode('bin')->isRequired()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
