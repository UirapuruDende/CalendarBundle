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
                ->scalarNode('occurrence_class')->isRequired()->end()
                ->scalarNode('backend_type')->defaultValue('ORM')->end()
                ->scalarNode('calendar_repository_service_name')->defaultValue('dende_calendar.calendar_repository')->end()
                ->scalarNode('calendar_factory_service_name')->defaultValue('dende_calendar.factory.calendar')->end()
                ->scalarNode('event_repository_service_name')->defaultValue('dende_calendar.event_repository')->end()
                ->scalarNode('event_factory_service_name')->defaultValue('dende_calendar.factory.event')->end()
                ->scalarNode('occurrence_repository_service_name')->defaultValue('dende_calendar.occurrences_repository')->end()
                ->scalarNode('occurrence_factory_service_name')->defaultValue('dende_calendar.factory.occurrence')->end()

                ->arrayNode('mapping')->useAttributeAsKey('name')->prototype('array')
                    ->children()
                        ->scalarNode('entity')->isRequired()->end()
                        ->arrayNode('map')->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                        ->end()
                        ->end()
                    ->defaultValue([])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
