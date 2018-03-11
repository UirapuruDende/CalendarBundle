<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\ORM;

use DateTime;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventId;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Dende\CalendarBundle\Tests\DataFixtures\BaseFixture;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended;
use Dende\CalendarBundle\Tests\Factory\OccurrenceFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventsData.
 */
final class EventsData extends BaseFixture implements ContainerAwareInterface
{
    /** @var string $dir */
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
        return 10;
    }

    public function insert(array $params = [])
    {
        Event::setOccurrenceClass(OccurrenceExtended::class);

        return Event::create(
            Uuid::fromString($params['eventId']),
            $params['title'],
            new DateTime($params['startDate']),
            new DateTime($params['endDate']),
            new EventType($params['type']),
            new Repetitions($params['repetitions']),
            $this->getReference($params['calendar'])
        );
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
    public function getContainer()
    {
        return $this->container;
    }
}
