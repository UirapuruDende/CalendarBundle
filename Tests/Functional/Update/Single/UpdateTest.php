<?php
namespace Dende\CalendarBundle\Tests\Functional\Update\Single;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\Tests\FunctionalTestCase;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended;
use Mockery as m;

class UpdateTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function updating_single_event()
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
            "update_event[type]" => EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2015-11-05 16:00",
            "update_event[endDate]" => "2015-11-05 17:30",
            "update_event[duration]" => 90,
            "update_event[title]" => "some-single-test-event-changed",
        ]);

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event-changed');

        /** @var OccurrenceExtended $occurrence */
        $occurrence = $this->em->getRepository(OccurrenceExtended::class)->findOneByEvent($event);

        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('some-single-test-event-changed', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-11-05 16:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-05 17:30", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-11-05 16:00", $occurrence->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-05 17:30", $occurrence->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals(90, $occurrence->duration()->minutes());
    }

    /**
     * @test
     */
    public function updating_single_occurrence_of_weekly_event()
    {
        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneByTitle('Test event number 01');
        $this->assertCount(13, $event->occurrences());
        $occurrence = $event->occurrences()->first();

        $crawler = $this->client->request('GET', '/calendar/occurrence/'.$occurrence->id());

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit_update.label')->form();

        $form->setValues([
            "update_event[calendar]" => $event->calendar()->id(),
            "update_event[type]" => EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2015-11-05 16:00",
            "update_event[endDate]" => "2015-11-05 17:30",
            "update_event[duration]" => 90,
            "update_event[title]" => "some-single-test-event-changed",
        ]);

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals("/calendar/", $this->client->getRequest()->getRequestUri());

        $event = $this->em->getRepository(Event::class)->findOneByTitle('some-single-test-event-changed');

        /** @var OccurrenceExtended $occurrence */
        $occurrence = $this->em->getRepository(OccurrenceExtended::class)->findOneByEvent($event);

        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('some-single-test-event-changed', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals("2015-11-05 16:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-05 17:30", $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals("2015-11-05 16:00", $occurrence->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2015-11-05 17:30", $occurrence->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals(90, $occurrence->duration()->minutes());
    }

    /**
     * test
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
            "update_event[type]" => EventType::TYPE_SINGLE,
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
}