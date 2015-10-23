<?php
namespace Dende\CalendarBundle\Tests\Unit\Service;

use DateTime;
use Dende\Calendar\Application\Factory\EventFactory;
use Dende\Calendar\Application\Factory\OccurrenceFactory;
use Dende\Calendar\Domain\Calendar\Event\Duration;
use Dende\Calendar\Domain\Calendar\Event\Occurrence\OccurrenceId;
use Dende\CalendarBundle\Service\OccurrencesProvider;
use Mockery as m;

final class OccurrenceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OccurrencesProvider
     */
    private $provider;

    protected function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_maps_occurrence_collection_to_array() {
        $calendar = m::mock('Dende\Calendar\Domain\Calendar');
        $start = new DateTime("now");
        $end = new DateTime("+1 hour");

        $event = EventFactory::create();

        $collection = [
            OccurrenceFactory::createFromArray([
                "id" => new OccurrenceId(),
                "startDate" => new DateTime("now"),
                "duration" => new Duration(90),
                "event" => $event
            ])
        ];

        $repository = m::mock('Dende\CalendarBundle\Repository\ORM\OccurrenceRepository');
        $repository->shouldReceive('findByCalendar')->with($calendar, $start, $end)->once()->andReturn($collection);

        $router = m::mock('Symfony\Bundle\FrameworkBundle\Routing\Router');

        $routeName = 'some-test-route-name';

        $this->provider = new OccurrencesProvider($repository, $router, $routeName);

        $json = $this->provider->get($calendar, $start, $end);

    }
}