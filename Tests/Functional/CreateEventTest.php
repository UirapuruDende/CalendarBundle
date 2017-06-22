<?php
namespace Dende\CalendarBundle\Tests\Functional;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\Tests\BaseFunctionalTest;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended;
use Mockery as m;

class CreateEventTest extends BaseFunctionalTest
{
    const FORMAT_DATETIME = 'Y-m-d H:i';

    /**
     * @test
     */
    public function adding_new_single_event()
    {
        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneBy(['title' => 'Brazilian Jiu Jitsu']);

        $crawler = $this->client->request('GET', '/calendar/occurrence/new');
        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $form->setValues([
            'create_event[calendar]'  => $calendar->id(),
            'create_event[type]'      => EventType::TYPE_SINGLE,
            'create_event[startDate]' => '2015-11-02 12:00',
            'create_event[endDate]'   => '2015-11-02 13:30',
            'create_event[title]'     => 'test-event-title',
        ]);

        $this->client->submit($form);

        $this->assertResponseCode();
        $this->assertEquals('/calendar/', $this->client->getRequest()->getRequestUri());

        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneByCalendarId($calendar->id());

        /** @var Event $event */
        $event = $calendar->events()->get(1);
        /** @var OccurrenceExtended $occurrence */
        $occurrence = $event->occurrences()->first();

        $this->assertCount(2, $calendar->events());
        $this->assertCount(1, $event->occurrences());
        $this->assertEquals('test-event-title', $event->title());
        $this->assertEquals('single', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals('2015-11-02 12:00', $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-11-02 13:30', $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('2015-11-02 12:00', $occurrence->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-11-02 13:30', $occurrence->endDate()->format(self::FORMAT_DATETIME));

//todo:        $this->assertEquals(90, $occurrence->duration()->minutes());
    }

    /**
     * @test
     */
    public function adding_new_weekly_event()
    {
        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneBy(['title' => 'Brazilian Jiu Jitsu']);

        $crawler = $this->client->request('GET', '/calendar/occurrence/new');

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $form->setValues([
            'create_event[calendar]'  => $calendar->id(),
            'create_event[type]'      => EventType::TYPE_WEEKLY,
            'create_event[startDate]' => '2015-09-01 12:00',
            'create_event[endDate]'   => '2015-09-30 13:30',
            'create_event[title]'     => 'Test weekly event 1',
        ]);

        $form['create_event[repetitions]'][0]->tick();
        $form['create_event[repetitions]'][2]->tick();
        $form['create_event[repetitions]'][4]->tick();

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals('/calendar/', $this->client->getRequest()->getRequestUri());

        /** @var Calendar $calendar */
        $calendar = $this->em->getRepository(Calendar::class)->findOneByCalendarId($calendar->id());

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneBy(['eventData.title' => 'Test weekly event 1']);

        $this->assertCount(2, $calendar->events());
        $this->assertCount(13, $event->occurrences());
        $this->assertEquals('Test weekly event 1', $event->title());
        $this->assertEquals('weekly', $event->type()->type());
        $this->assertEquals(90, $event->duration()->minutes());
        $this->assertEquals('2015-09-01 12:00', $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-30 13:30', $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('2015-09-02 12:00', $event->occurrences()->get(0)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-02 13:30', $event->occurrences()->get(0)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-04 12:00', $event->occurrences()->get(1)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-04 13:30', $event->occurrences()->get(1)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('2015-09-07 12:00', $event->occurrences()->get(2)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-07 13:30', $event->occurrences()->get(2)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-09 12:00', $event->occurrences()->get(3)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-09 13:30', $event->occurrences()->get(3)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-11 12:00', $event->occurrences()->get(4)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-11 13:30', $event->occurrences()->get(4)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('2015-09-14 12:00', $event->occurrences()->get(5)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-14 13:30', $event->occurrences()->get(5)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-16 12:00', $event->occurrences()->get(6)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-16 13:30', $event->occurrences()->get(6)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-18 12:00', $event->occurrences()->get(7)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-18 13:30', $event->occurrences()->get(7)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('2015-09-21 12:00', $event->occurrences()->get(8)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-21 13:30', $event->occurrences()->get(8)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-23 12:00', $event->occurrences()->get(9)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-23 13:30', $event->occurrences()->get(9)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-25 12:00', $event->occurrences()->get(10)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-25 13:30', $event->occurrences()->get(10)->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('2015-09-28 12:00', $event->occurrences()->get(11)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-28 13:30', $event->occurrences()->get(11)->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-30 12:00', $event->occurrences()->get(12)->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-30 13:30', $event->occurrences()->get(12)->endDate()->format(self::FORMAT_DATETIME));
    }

    /**
     * @test
     */
    public function adding_new_weekly_event_to_a_new_calendar()
    {
        $crawler = $this->client->request('GET', '/calendar/occurrence/new');

        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $form->setValues([
            'create_event[calendar]'        => null,
            'create_event[newCalendarName]' => 'i am new calendar added',
            'create_event[type]'            => EventType::TYPE_WEEKLY,
            'create_event[startDate]'       => '2015-09-01 12:00',
            'create_event[endDate]'         => '2015-09-30 13:30',
            'create_event[title]'           => 'Test weekly event for new calendar',
        ]);

        $form['create_event[repetitions]'][0]->tick();
        $form['create_event[repetitions]'][2]->tick();
        $form['create_event[repetitions]'][4]->tick();

        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals('/calendar/', $this->client->getRequest()->getRequestUri());

        /** @var Event $event */
        $event = $this->em->getRepository(Event::class)->findOneBy(['eventData.title' => 'Test weekly event for new calendar']);

        $this->assertCount(13, $event->occurrences());
        $this->assertEquals('2015-09-01 12:00', $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals('2015-09-30 13:30', $event->endDate()->format(self::FORMAT_DATETIME));

        $this->assertEquals('i am new calendar added', $event->calendar()->title());
    }
}
