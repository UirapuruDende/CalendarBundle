<?php
namespace Dende\CalendarBundle\Tests\Functional\Update\Single;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\Tests\BaseFunctionalTest;
use Mockery as m;

class UpdateSingleToWeeklyTest extends BaseFunctionalTest
{
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
            "update_event[type]" => EventType::TYPE_WEEKLY,
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
}