<?php
namespace Dende\CalendarBundle\Tests\Functional\Update\Weekly\Overwrite;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\Tests\FunctionalTestCase;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;
use Traversable;

/**
 * @todo: remove, type convertion!
 */
class UpdateWeeklyToSingleTest extends FunctionalTestCase
{
    /**
     * test
     */
    public function testUpdate()
    {
        /** @var OccurrenceExtended $occurrence */
        $occurrence = $this->fixtures->getReference('occurrence-05-04');
        $event = $occurrence->event();
        $this->assertCount(5, $event->occurrences());

        $crawler = $this->client->request('GET', '/calendar/occurrence/' . $occurrence->id());

        $button = $crawler->selectButton('dende_calendar.form.submit_update.label');

        $md5Title = md5(microtime());

        $form = $button->form([
            "update_event[calendar]" => $occurrence->event()->calendar()->id(),
            "update_event[type]" => EventType::TYPE_SINGLE,
            "update_event[startDate]" => "2017-09-01 16:00",
            "update_event[endDate]" => "2017-09-01 16:45",
            "update_event[method]" => "overwrite",
            "update_event[duration]" => 45,
            "update_event[title]" => $md5Title,
        ]);

        $this->client->submit($form);

        $this->assertResponseCode();
        $this->assertFormHasNoErrors('update_event');

        $event = $this->em->getRepository(Event::class)->find($event->id());

        /** @var ArrayCollection|Traversable|OccurrenceExtended[] $occurrences */
        $occurrences = $this->em->getRepository(OccurrenceExtended::class)->findByEvent($event);
        $this->assertCount(1, $occurrences);
        $this->assertCount(1, $event->occurrences());
        $this->assertCount(1, $event->occurrences()->filter(function(OccurrenceExtended $occurrence){
            return !$occurrence->isDeleted();
        }));

        $occurrence = array_pop($occurrences);

        $this->assertEquals("2017-09-01 16:00", $event->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2017-09-01 16:45", $event->endDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2017-09-01 16:00", $occurrence->startDate()->format(self::FORMAT_DATETIME));
        $this->assertEquals("2017-09-01 16:45", $occurrence->endDate()->format(self::FORMAT_DATETIME));
    }
}