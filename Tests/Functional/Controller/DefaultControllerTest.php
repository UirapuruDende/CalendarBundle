<?php
namespace Dende\CalendarBundle\Tests\Functional\Controller;

use AppKernel;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\CalendarBundle\Tests\BaseFunctionalTest;
use Doctrine\ORM\EntityManager;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class DefaultControllerTest
 * @package Dende\CalendarBundle\Tests\Functional\Controller
 * @todo test if flash messages appear (without translating - a nulltranslator needed!)
 */
final class DefaultControllerTest extends BaseFunctionalTest
{
    /**
     * @var Calendar
     */
    private $calendar;

    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        parent::setUp();
        $this->em = $this->container->get("doctrine.orm.entity_manager");
        $this->calendar = $this->em->getRepository("Calendar:Calendar")->findOneByName('Brazilian Jiu Jitsu');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testMainPage()
    {
        $headers = array('CONTENT_TYPE' => 'text/html');
        $content = array('parameter' => 'value');

        $crawler = $this->client->request('GET', '/calendar/', [], [], $headers, $content);
        $this->assertEquals(200, $this->getStatusCode());
    }

    /**
     * @test
     */
    public function adding_new_single_event()
    {
        $crawler = $this->client->request('GET', '/calendar/occurrence/new');
        $this->assertEquals(200, $this->getStatusCode());

        $form = $crawler->selectButton('create_event[submit]')->form();

        $form->setValues([
            "create_event[calendar]" => $this->calendar->id(),
            "create_event[type]" => Calendar\Event\EventType::TYPE_SINGLE,
            "create_event[startDate]" => "2015-11-02 12:00",
            "create_event[endDate]" => "2015-11-02 13:30",
            "create_event[duration]" => 90,
            "create_event[title]" => "test-event-title",
        ]);

        $this->client->submit($form);

        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->refresh($this->calendar);

        /** @var Event $event */
        $event = $this->calendar->events()->get(1);
        /** @var Occurrence $occurrence */
        $occurrence = $event->occurrences()->first();

        $this->assertCount(2, $this->calendar->events());
        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('test-event-title', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-11-02 12:00", $event->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-11-02 13:30", $event->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-11-02 12:00", $occurrence->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-11-02 13:30", $occurrence->endDate()->format("Y-m-d H:i"));

        $this->assertEquals(90, $occurrence->duration()->minutes());
    }

    /**
     * @test
     */
    public function adding_new_weekly_event()
    {
        $crawler = $this->client->request('GET', '/calendar/occurrence/new');

        $this->assertEquals(200, $this->getStatusCode());

        $form = $crawler->selectButton('create_event[submit]')->form();

        $form->setValues([
            "create_event[calendar]" => $this->calendar->id(),
            "create_event[type]" => Calendar\Event\EventType::TYPE_WEEKLY,
            "create_event[startDate]" => "2015-09-01 12:00",
            "create_event[endDate]" => "2015-09-30 13:30",
            "create_event[duration]" => 90,
            "create_event[title]" => "Test weekly event 1",
        ]);

        $form["create_event[repetitionDays]"][0]->tick();
        $form["create_event[repetitionDays]"][2]->tick();
        $form["create_event[repetitionDays]"][4]->tick();

        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->refresh($this->calendar);

        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('Test weekly event 1');

        $this->assertCount(2, $this->calendar->events());
        $this->assertCount(13, $event->occurrences());
        $this->assertEquals('Test weekly event 1', $event->title());
        $this->assertEquals('weekly', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-09-01 12:00", $event->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 13:30", $event->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-02 12:00", $event->occurrences()->get(0)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-02 13:30", $event->occurrences()->get(0)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-04 12:00", $event->occurrences()->get(1)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-04 13:30", $event->occurrences()->get(1)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-07 12:00", $event->occurrences()->get(2)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-07 13:30", $event->occurrences()->get(2)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-09 12:00", $event->occurrences()->get(3)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-09 13:30", $event->occurrences()->get(3)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-11 12:00", $event->occurrences()->get(4)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-11 13:30", $event->occurrences()->get(4)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-14 12:00", $event->occurrences()->get(5)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-14 13:30", $event->occurrences()->get(5)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-16 12:00", $event->occurrences()->get(6)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-16 13:30", $event->occurrences()->get(6)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-18 12:00", $event->occurrences()->get(7)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-18 13:30", $event->occurrences()->get(7)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-21 12:00", $event->occurrences()->get(8)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-21 13:30", $event->occurrences()->get(8)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-23 12:00", $event->occurrences()->get(9)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-23 13:30", $event->occurrences()->get(9)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-25 12:00", $event->occurrences()->get(10)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-25 13:30", $event->occurrences()->get(10)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-28 12:00", $event->occurrences()->get(11)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-28 13:30", $event->occurrences()->get(11)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 12:00", $event->occurrences()->get(12)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 13:30", $event->occurrences()->get(12)->endDate()->format("Y-m-d H:i"));
    }

    /**
     * @test
     */
    public function adding_new_weekly_event_to_a_new_calendar()
    {
        $crawler = $this->client->request('GET', '/calendar/occurrence/new');

        $this->assertEquals(200, $this->getStatusCode());

        $form = $crawler->selectButton('create_event[submit]')->form();

        $form->setValues([
            "create_event[calendar]" => $this->calendar->id(),
            "create_event[new_calendar_name]" => 'i am new calendar added',
            "create_event[type]" => Calendar\Event\EventType::TYPE_WEEKLY,
            "create_event[startDate]" => "2015-09-01 12:00",
            "create_event[endDate]" => "2015-09-30 13:30",
            "create_event[duration]" => 90,
            "create_event[title]" => "Test weekly event for new calendar",
        ]);

        $form["create_event[repetitionDays]"][0]->tick();
        $form["create_event[repetitionDays]"][2]->tick();
        $form["create_event[repetitionDays]"][4]->tick();

        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->refresh($this->calendar);

        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('Test weekly event for new calendar');

        $this->assertCount(13, $event->occurrences());
        $this->assertEquals("2015-09-01 12:00", $event->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 13:30", $event->endDate()->format("Y-m-d H:i"));

        $this->assertEquals('i am new calendar added', $event->calendar()->name());
    }

    /**
     * @test
     */
    public function updating_single_event_without_type_change()
    {
        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());

        $this->assertEquals(200, $this->getStatusCode());

        $form = $crawler->selectButton('update_event[submit]')->form();

        $form->setValues([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[type]" => Calendar\Event\EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2015-11-05 16:00",
            "update_event[endDate]" => "2015-11-05 17:30",
            "update_event[duration]" => 90,
            "update_event[title]" => "some-single-test-event-changed",
        ]);

        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->refresh($event);

        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('some-single-test-event-changed');

        /** @var Occurrence $occurrence */
        $occurrence = $event->occurrences()->first();

        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('some-single-test-event-changed', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-11-05 16:00", $event->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-11-05 17:30", $event->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-11-05 16:00", $occurrence->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-11-05 17:30", $occurrence->endDate()->format("Y-m-d H:i"));
        $this->assertEquals(90, $occurrence->duration()->minutes());
    }


    /**
     * @test
     */
    public function updating_single_event_with_calendar_creation()
    {
        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());

        $this->assertEquals(200, $this->getStatusCode());

        $form = $crawler->selectButton('update_event[submit]')->form();

        $form->setValues([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[new_calendar_name]" => 'i am some next calendar added',
            "update_event[type]" => Calendar\Event\EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2015-11-05 16:00",
            "update_event[endDate]" => "2015-11-05 17:30",
            "update_event[duration]" => 90,
            "update_event[title]" => "some-single-test-event-changed",
        ]);

        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->refresh($event);

        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('some-single-test-event-changed');

        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('some-single-test-event-changed', $event->title());
        $this->assertEquals('i am some next calendar added', $event->calendar()->name());
    }

    /**
     * @test
     */
    public function updating_single_event_to_weekly_event()
    {
        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());
        $this->assertEquals(200, $this->getStatusCode());

        $form = $crawler->selectButton('update_event[submit]')->form();

        $form->setValues([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[type]" => Calendar\Event\EventType::TYPE_WEEKLY,
            "update_event[startDate]" => "2015-09-01 16:00",
            "update_event[endDate]" => "2015-09-30 17:00",
            "update_event[duration]" => 60,
            "update_event[title]" => "some-weekly-test-event-changed",
        ]);

        $form["update_event[repetitionDays]"][0]->tick();
        $form["update_event[repetitionDays]"][1]->untick();
        $form["update_event[repetitionDays]"][2]->tick();
        $form["update_event[repetitionDays]"][4]->tick();

        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->refresh($this->calendar);
        $this->em->refresh($event);

        $this->assertEquals('some-weekly-test-event-changed', $event->title());
        $this->assertEquals('weekly', $event->type()->type());
        $this->assertCount(1, $this->calendar->events());
        $this->assertCount(13, $event->occurrences());
        $this->assertEquals(60, $event->duration()->minutes());
        $this->assertEquals("2015-09-01 16:00", $event->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 17:00", $event->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-02 16:00", $event->occurrences()->get(0)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-02 17:00", $event->occurrences()->get(0)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-04 16:00", $event->occurrences()->get(1)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-04 17:00", $event->occurrences()->get(1)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-07 16:00", $event->occurrences()->get(2)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-07 17:00", $event->occurrences()->get(2)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-09 16:00", $event->occurrences()->get(3)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-09 17:00", $event->occurrences()->get(3)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-11 16:00", $event->occurrences()->get(4)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-11 17:00", $event->occurrences()->get(4)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-14 16:00", $event->occurrences()->get(5)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-14 17:00", $event->occurrences()->get(5)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-16 16:00", $event->occurrences()->get(6)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-16 17:00", $event->occurrences()->get(6)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-18 16:00", $event->occurrences()->get(7)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-18 17:00", $event->occurrences()->get(7)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-21 16:00", $event->occurrences()->get(8)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-21 17:00", $event->occurrences()->get(8)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-23 16:00", $event->occurrences()->get(9)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-23 17:00", $event->occurrences()->get(9)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-25 16:00", $event->occurrences()->get(10)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-25 17:00", $event->occurrences()->get(10)->endDate()->format("Y-m-d H:i"));

        $this->assertEquals("2015-09-28 16:00", $event->occurrences()->get(11)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-28 17:00", $event->occurrences()->get(11)->endDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 16:00", $event->occurrences()->get(12)->startDate()->format("Y-m-d H:i"));
        $this->assertEquals("2015-09-30 17:00", $event->occurrences()->get(12)->endDate()->format("Y-m-d H:i"));
    }

    /**
     * @test
     */
    public function deleting_single_event_with_his_occurrence()
    {
        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $calendarId = $event->calendar()->id();
        $eventId = $event->id();
        $occurrenceId = $occurrence->id();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrenceId);
        $this->assertEquals(200, $this->getStatusCode());

        $formElement = $crawler->filter('form[name="update_event"]')->first();
        $this->assertCount(2, $formElement->filter('button'));

        $form = $crawler->selectButton('update_event[delete_event]')->form();
        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->assertInstanceOf(Calendar::class, $this->em->getRepository("Calendar:Calendar")->findOneById($calendarId));
        $this->assertNotInstanceOf(Event::class, $this->em->getRepository("Calendar:Calendar\Event")->findOneById($eventId));
        $this->assertNotInstanceOf(Occurrence::class, $this->em->getRepository("Calendar:Calendar\Event\Occurrence")->findOneById($occurrenceId));
    }

    /**
     * @test
     */
    public function deleting_weekly_event_with_all_his_occurrences()
    {
        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('Test event number 02');
        $this->assertCount(13, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $calendarId = $event->calendar()->id();
        $eventId = $event->id();
        $occurrenceId = $occurrence->id();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrenceId);
        $this->assertEquals(200, $this->getStatusCode());

        $formElement = $crawler->filter('form[name="update_event"]')->first();
        $this->assertCount(3, $formElement->filter('button'));

        $form = $crawler->selectButton('update_event[delete_event]')->form();
        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->assertNotNull(Calendar::class, $this->em->getRepository("Calendar:Calendar")->findOneById($calendarId));
        $this->assertNull($this->em->getRepository("Calendar:Calendar\Event")->findOneById($eventId));
        $this->assertNull(Occurrence::class, $this->em->getRepository("Calendar:Calendar\Event\Occurrence")->findOneById($occurrenceId));
    }

    /**
     * @test
     */
    public function deleting_whole_calendar()
    {
        /** @var Event $event */
        $event = $this->em->getRepository("Calendar:Calendar\Event")->findOneByTitle('Test event number 02');
        $this->em->getRepository("Calendar:Calendar")->remove($event->calendar());

        $calendarId = $event->calendar()->id();
        $eventId = $event->id();

        $this->assertNotInstanceOf(Calendar::class, $this->em->getRepository("Calendar:Calendar")->findById($calendarId));
        $this->assertNotInstanceOf(Event::class, $this->em->getRepository("Calendar:Calendar\Event")->findById($eventId));
        $this->assertCount(0, $this->em->getRepository("Calendar:Calendar\Event\Occurrence")->findByEvent($event));
    }
}
