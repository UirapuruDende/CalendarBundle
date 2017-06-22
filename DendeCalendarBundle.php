<?php
namespace Dende\CalendarBundle;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\CalendarBundle\DependencyInjection\CompilerPass\UpdateStrategiesPass;
use Dende\CalendarBundle\Tests\Factory\OccurrenceFactory;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DendeCalendarBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        Event::setFactoryClass(OccurrenceFactory::class);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->addRegisterMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = [realpath(__DIR__ . '/Resources/config/doctrine') => 'Dende\Calendar\Domain'];

        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';

        if (class_exists($ormCompilerClass)) {
            $registerMappingCompilerPass = DoctrineOrmMappingsPass::createYamlMappingDriver(
                $mappings,
                ['dende_calendar.model_manager_name'],
                'dende_calendar.backend_type_orm',
                ['Calendar' => 'Dende\Calendar\Domain']
            );

            $container->addCompilerPass($registerMappingCompilerPass);
        }

        $container->addCompilerPass(new UpdateStrategiesPass());
    }
}
