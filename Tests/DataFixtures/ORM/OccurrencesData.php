<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\ORM;

use DateTime;
use Dende\Calendar\Domain\Calendar\Event\Occurrence\OccurrenceDuration;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended as Occurrence;
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
        $factory = $this->container->get("dende_calendar.factory.occurrence");

        $occurrence = $factory->createFromArray([
            'startDate' => new DateTime($params["startDate"]),
            'duration'  => new OccurrenceDuration($params["duration"]),
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
