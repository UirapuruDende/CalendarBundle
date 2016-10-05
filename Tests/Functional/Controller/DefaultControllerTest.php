<?php
namespace Dende\CalendarBundle\Tests\Functional\Controller;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\CalendarBundle\Tests\BaseFunctionalTest;
use Doctrine\ORM\EntityManager;
use Mockery as m;

/**
 * Class DefaultControllerTest
 * @package Dende\CalendarBundle\Tests\Functional\Controller
 */
class DefaultControllerTest extends BaseFunctionalTest
{
    const FORMAT_DATETIME = "Y-m-d H:i";

    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function test_main_page()
    {
        $headers = array('CONTENT_TYPE' => 'text/html');
        $content = array('parameter' => 'value');

        $crawler = $this->client->request('GET', '/calendar/', [], [], $headers, $content);
        $this->assertResponseCode();
    }

    /**
     * @test
     */
    public function adding_new_single_event()
    {
        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneByName('Brazilian Jiu Jitsu');

        $crawler = $this->client->request('GET', '/calendar/occurrence/new');
        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $form->setValues([
            "create_event[calendar]" => $calendar->id(),
            "create_event[type]" => Calendar\Event\EventType::TYPE_SINGLE,
            "create_event[startDate]" => "2015-11-02 12:00",
            "create_event[endDate]" => "2015-11-02 13:30",
            "create_event[duration]" => 90,
            "create_event[title]" => "test-event-title",
        ]);

        $this->client->submit($form);

        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->find($calendar->id());

        /** @var Event $event */
        $event = $calendar->events()->get(1);
        /** @var Occurrence $occurrence */
        $occurrence = $event->occurrences()->first();

        $this->assertCount(2, $calendar->events());
        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('test-event-title', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-11-02 12:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-02 13:30", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-11-02 12:00", $occurrence->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-02 13:30", $occurrence->endDate()->format(self::FORMAT_DATETIME));

//todo:        $this->assertEquals(90, $occurrence->duration()->minutes());
    }

    /**
     * @test
     */
    public function adding_new_weekly_event()
    {
        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneByName('Brazilian Jiu Jitsu');

        $crawler = $this->client->request('GET', '/calendar/occurrence/new');

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $form->setValues([
            "create_event[calendar]" => $calendar->id(),
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
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->find($calendar->id());

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('Test weekly event 1');

        $this->assertCount(2, $calendar->events());
        $this->assertCount(13, $event->occurrences());
        $this->assertEquals('Test weekly event 1', $event->title());
        $this->assertEquals('weekly', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-09-01 12:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 13:30", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-09-02 12:00", $event->occurrences()->get(0)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-02 13:30", $event->occurrences()->get(0)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-04 12:00", $event->occurrences()->get(1)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-04 13:30", $event->occurrences()->get(1)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-09-07 12:00", $event->occurrences()->get(2)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-07 13:30", $event->occurrences()->get(2)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-09 12:00", $event->occurrences()->get(3)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-09 13:30", $event->occurrences()->get(3)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-11 12:00", $event->occurrences()->get(4)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-11 13:30", $event->occurrences()->get(4)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-09-14 12:00", $event->occurrences()->get(5)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-14 13:30", $event->occurrences()->get(5)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-16 12:00", $event->occurrences()->get(6)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-16 13:30", $event->occurrences()->get(6)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-18 12:00", $event->occurrences()->get(7)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-18 13:30", $event->occurrences()->get(7)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-09-21 12:00", $event->occurrences()->get(8)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-21 13:30", $event->occurrences()->get(8)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-23 12:00", $event->occurrences()->get(9)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-23 13:30", $event->occurrences()->get(9)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-25 12:00", $event->occurrences()->get(10)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-25 13:30", $event->occurrences()->get(10)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-09-28 12:00", $event->occurrences()->get(11)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-28 13:30", $event->occurrences()->get(11)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 12:00", $event->occurrences()->get(12)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 13:30", $event->occurrences()->get(12)->endDate()->format(self::FORMAT_DATETIME));
    }

    /**
     * @test
     */
    public function adding_new_weekly_event_to_a_new_calendar()
    {
        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneByName('Brazilian Jiu Jitsu');

        $crawler = $this->client->request('GET', '/calendar/occurrence/new');

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $form->setValues([
            "create_event[calendar]" => $calendar->id(),
            "create_event[newCalendarName]" => 'i am new calendar added',
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
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $calendar = $this->em->getRepository(Calendar::class)->find($calendar->id());

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('Test weekly event for new calendar');

        $this->assertCount(13, $event->occurrences());
        $this->assertEquals("2015-09-01 12:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 13:30", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('i am new calendar added', $event->calendar()->name());
    }

    /**
     * @test
     */
    public function updating_single_event_without_type_change()
    {
        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit_update.label')->form();

        $form->setValues([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[type]" => Calendar\Event\EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2015-11-05 16:00",
            "update_event[endDate]" => "2015-11-05 17:30",
            "update_event[duration]" => 90,
            "update_event[title]" => "some-single-test-event-changed",
        ]);

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event-changed');

        /** @var Occurrence $occurrence */
        $occurrence = $event->occurrences()->first();
        $this->em->refresh($occurrence);

        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('some-single-test-event-changed', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-11-05 16:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-05 17:30", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-11-05 16:00", $occurrence->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-05 17:30", $occurrence->endDate()->format(self::FORMAT_DATETIME));
//todo:        $this->assertEquals(90, $occurrence->duration()->minutes());
    }


    /**
     * @test
     */
    public function updating_single_event_with_calendar_creation()
    {
        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit_update.label')->form();

        $form->setValues([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[newCalendarName]" => 'i am some next calendar added',
            "update_event[type]" => Calendar\Event\EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2015-11-05 16:00",
            "update_event[endDate]" => "2015-11-05 17:30",
            "update_event[duration]" => 90,
            "update_event[title]" => "some-single-test-event-changed",
        ]);

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event-changed');

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
        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());
        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit_update.label')->form([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[type]" => Calendar\Event\EventType::TYPE_WEEKLY,
            "update_event[startDate]" => "2015-09-01 16:00",
            "update_event[endDate]" => "2015-09-30 17:00",
            "update_event[duration]" => 60,
            "update_event[title]" => "some-weekly-test-event-changed",
        ]);

        $form["update_event[repetitionDays]"][0]->tick(); // monday
        $form["update_event[repetitionDays]"][1]->untick();
        $form["update_event[repetitionDays]"][2]->tick(); // wednesday
        $form["update_event[repetitionDays]"][3]->untick();
        $form["update_event[repetitionDays]"][4]->tick(); // friday
        $form["update_event[repetitionDays]"][5]->untick();
        $form["update_event[repetitionDays]"][6]->untick();

        // expected days:
        //            2.9.2015  4.9.2015
        //  7.9.2015  9.9.2015 11.9.2015
        // 14.9.2015 16.9.2015 18.9.2015
        // 21.9.2015 23.9.2015 25.9.2015
        // 28.9.2015 30.9.2015

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->em->clear();

        $event = $this->em->getRepository(Event::class)->findOneById($event->id());

        $this->assertEquals('some-weekly-test-event-changed', $event->title());
        $this->assertCount(13, $event->occurrences());

//        $this->assertCount(1, $event->occurrences()->filter(function(Occurrence $occurrence){
//            return $occurrence->isDeleted();
//        }));

        $this->assertCount(13, $event->occurrences()->filter(function(Occurrence $occurrence){
            return !$occurrence->isDeleted();
        }));

        $this->assertCount(1, $event->calendar()->events());

        $this->assertEquals(60, $event->duration()->minutes());
        $this->assertEquals('weekly', $event->type()->type());

        $this->assertEquals("2015-09-01 16:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 17:00", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-09-02 16:00", $event->occurrences()->get(0)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-02 17:00", $event->occurrences()->get(0)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-04 16:00", $event->occurrences()->get(1)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-04 17:00", $event->occurrences()->get(1)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-07 16:00", $event->occurrences()->get(2)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-07 17:00", $event->occurrences()->get(2)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-09 16:00", $event->occurrences()->get(3)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-09 17:00", $event->occurrences()->get(3)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-11 16:00", $event->occurrences()->get(4)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-11 17:00", $event->occurrences()->get(4)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-14 16:00", $event->occurrences()->get(5)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-14 17:00", $event->occurrences()->get(5)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-16 16:00", $event->occurrences()->get(6)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-16 17:00", $event->occurrences()->get(6)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-18 16:00", $event->occurrences()->get(7)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-18 17:00", $event->occurrences()->get(7)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-21 16:00", $event->occurrences()->get(8)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-21 17:00", $event->occurrences()->get(8)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-23 16:00", $event->occurrences()->get(9)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-23 17:00", $event->occurrences()->get(9)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-25 16:00", $event->occurrences()->get(10)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-25 17:00", $event->occurrences()->get(10)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-28 16:00", $event->occurrences()->get(11)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-28 17:00", $event->occurrences()->get(11)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 16:00", $event->occurrences()->get(12)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-09-30 17:00", $event->occurrences()->get(12)->endDate()->format(self::FORMAT_DATETIME));
    }

    /**
     * @test
     */
    public function deleting_single_event_with_his_occurrence()
    {
        $this->markTestSkipped();

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event');
        $this->assertCount(1, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $calendarId = $event->calendar()->id();
        $eventId = $event->id();
        $occurrenceId = $occurrence->id();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrenceId);
        $this->assertResponseCode();

        $formElement = $crawler->filter('form[name="update_event"]')->first();
        $this->assertCount(2, $formElement->filter('button'));

        $form = $crawler->selectButton('update_event[delete_event]')->form();
        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->assertInstanceOf(Calendar::class, $this->em->getRepository(Calendar::class)->findOneById($calendarId));
        $this->assertNull($this->em->getRepository(Event::class)->findOneById($eventId));
        $this->assertCount(0, $this->em->getRepository(Occurrence::class)->findById($occurrenceId));
    }

    /**
     * @test
     */
    public function deleting_weekly_event_with_all_his_occurrences()
    {
        $this->markTestSkipped();

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('Test event number 02');
        $this->assertCount(13, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $calendarId = $event->calendar()->id();
        $eventId = $event->id();
        $occurrenceId = $occurrence->id();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrenceId);
        $this->assertResponseCode();

        $formElement = $crawler->filter('form[name="update_event"]')->first();
        $this->assertCount(3, $formElement->filter('button'));

        $form = $crawler->selectButton('update_event[delete_event]')->form();
        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $this->assertNotNull(Calendar::class, $this->em->getRepository(Calendar::class)->findOneById($calendarId));

        $this->assertNull($this->em->getRepository(Event::class)->findOneById($eventId));
        $this->assertNull($this->em->getRepository(Occurrence::class)->findAllByEvent($event));
        $this->assertInstanceOf(Occurrence::class, $this->em->getRepository(Occurrence::class)->findOneById($occurrenceId));
    }

    /**
     * @test
     */
    public function deleting_whole_calendar()
    {
        $this->markTestSkipped();

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('Test event number 02');
        $this->em->getRepository(Calendar::class)->remove($event->calendar());

        $calendarId = $event->calendar()->id();
        $eventId = $event->id();

        $this->assertNotInstanceOf(Calendar::class, $this->em->getRepository(Calendar::class)->findById($calendarId));
        $this->assertNotInstanceOf(Event::class, $this->em->getRepository(Event::class)->findById($eventId));
        $this->assertCount(0, $this->em->getRepository(Occurrence::class)->findByEvent($event));
    }
}
