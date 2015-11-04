<?php
namespace Dende\CalendarBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DendeCalendarBundle extends Bundle
{
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
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine') => 'Dende\Calendar\Domain',
        );

        $registerMappingCompilerPass = DoctrineOrmMappingsPass::createYamlMappingDriver(
            $mappings,
            [
                'dende_calendar.entity_manager',
                'doctrine.orm.club_entity_manager'
            ],
            'dende_calendar.backend_type_orm',
            ["Calendar" => 'Dende\Calendar\Domain']
        );

        $container->addCompilerPass($registerMappingCompilerPass);
    }
}
