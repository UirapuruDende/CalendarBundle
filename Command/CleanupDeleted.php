<?php
namespace Dende\CalendarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CleanupDeleted extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Container
     */
    protected $container;

    protected function configure()
    {
        $this
            ->setName('calendar:cleanup')
            ->setDescription('Removes deleted items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $qb = $this->container->get('dende_calendar.occurrences_repository')->createQueryBuilder('o');
        $qb->where('o.deletedAt is not NULL');
    }
}
