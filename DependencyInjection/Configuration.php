<?php
namespace Dende\CalendarBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dende_calendar');

        $rootNode
            ->children()
                ->scalarNode('model_manager_name')->defaultValue('default')->end()
                ->scalarNode('backend_type')->defaultValue('ORM')->end()
                ->scalarNode('occurrence_repository_service_name')->defaultValue('dende_calendar.occurrences_repository')->end()
                ->scalarNode('occurrence_factory_service_name')->defaultValue('dende_calendar.factory.occurrence')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
