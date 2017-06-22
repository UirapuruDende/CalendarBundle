<?php
namespace Dende\CalendarBundle\Tests\Unit\Service;

use DateTime;
use Dende\Calendar\Application\Factory\EventFactory;
use Dende\Calendar\Application\Factory\OccurrenceFactory;
use Dende\Calendar\Application\Generator\InMemory\IdGenerator;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\Duration;
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
    public function it_maps_occurrence_collection_to_array()
    {
        $calendar = m::mock(Calendar::class);
        $start = new DateTime('now');
        $end = new DateTime('+1 hour');

        $event = (new EventFactory(new IdGenerator()))->createFromArray([
            'title'    => 'Event Title',
            'start'    => new DateTime('now'),
            'end'      => new DateTime('+1 hour'),
            'duration' => new Duration(60),
        ]);

        $collection = [
            (new OccurrenceFactory(new IdGenerator()))->createFromArray([
                'id'        => '123',
                'startDate' => new DateTime('now'),
                'duration'  => 60,
                'event'     => $event,
            ]),
        ];

        $routeName = 'some-test-route-name';

        $repository = m::mock('Dende\CalendarBundle\Repository\ORM\OccurrenceRepository');
        $repository->shouldReceive('findByCalendar')->with($calendar, $start, $end)->once()->andReturn($collection);

        $router = m::mock('Symfony\Bundle\FrameworkBundle\Routing\Router');
        $router->shouldReceive('generate')->with($routeName, ['occurrenceId' => '123'])->once()->andReturn('url/to/occurrence');

        $this->provider = new OccurrencesProvider($repository, $router, $routeName);

        $result = $this->provider->get($calendar, $start, $end);

        $event = $result[0];

        $this->assertCount(1, $result);
        $this->assertEquals($event['title'], 'Event Title');
        $this->assertEquals($event['start'], (new DateTime())->format('Y-m-d H:i:s'), null, 1);
        $this->assertEquals($event['end'], (new DateTime('+1 hour'))->format('Y-m-d H:i:s'), null, 1);
        $this->assertEquals($event['url'], 'url/to/occurrence');
    }
}
