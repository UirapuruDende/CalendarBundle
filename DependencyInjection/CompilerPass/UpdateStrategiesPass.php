<?php
namespace Dende\CalendarBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class UpdateStrategiesPass.
 */
class UpdateStrategiesPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('dende_calendar.manager.update_occurrence')) {
            return;
        }

        $definition = $container->findDefinition('dende_calendar.manager.update_occurrence');

        $taggedServices = $container->findTaggedServiceIds('dende_calendar.update_strategy');

        foreach ($taggedServices as $id => $tags) {
            /** @var Definition $taggedDefinition */
            $taggedDefinition = $container->findDefinition($id);

            $class = array_values(explode('\\', $taggedDefinition->getClass()));
            $name = strtolower(end($class));

            $definition->addMethodCall('addStrategy', [$name, new Reference($id)]);
        }
    }
}
