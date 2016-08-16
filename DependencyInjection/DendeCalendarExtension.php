<?php
namespace Dende\CalendarBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DendeCalendarExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter("dende_calendar.model_manager_name", $config["model_manager_name"]);

        if ($config["backend_type"] === "ORM")
        {
            $container->setParameter("dende_calendar.backend_type_orm", true);
        }

        $container->setParameter(
            "dende_calendar.calendar_repository_service_name",
            $config["calendar_repository_service_name"]
        );

        $container->setParameter(
            "dende_calendar.calendar_factory_service_name",
            $config["calendar_factory_service_name"]
        );
        $container->setParameter(
            "dende_calendar.event_repository_service_name",
            $config["event_repository_service_name"]
        );

        $container->setParameter(
            "dende_calendar.event_factory_service_name",
            $config["event_factory_service_name"]
        );

        $container->setParameter(
            "dende_calendar.occurrence_repository_service_name",
            $config["occurrence_repository_service_name"]
        );

        $container->setParameter(
            "dende_calendar.occurrence_factory_service_name",
            $config["occurrence_factory_service_name"]
        );

        $container->setParameter(
            'dende_calendar.mapping', $config['mapping']
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
