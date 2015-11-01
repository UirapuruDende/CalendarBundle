<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM;

use DateTime;
use Dende\Calendar\Application\Factory\OccurrenceFactory;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\Duration;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\CommonBundle\DataFixtures\BaseFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OccurrencesData
 * @package Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM
 */
final class OccurrencesData extends BaseFixture implements ContainerAwareInterface
{
    /**
     * @var string
     */
    protected $dir = __DIR__;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return int
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * @param $params
     * @return Occurrence
     */
    public function insert($params)
    {
        $occurrence = $this->getContainer()->get('dende_calendar.factory.occurrence')->createFromArray([
            'startDate' => new DateTime($params["startDate"]),
            'duration'  => new Duration($params["duration"]),
            'event'     => $this->getReference($params["event"]),
        ]);
                
        return $occurrence;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->container;
    }
}
