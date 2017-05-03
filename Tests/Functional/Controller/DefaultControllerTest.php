<?php
namespace Dende\CalendarBundle\Tests\Functional\Controller;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\CalendarBundle\Tests\BaseFunctionalTest;
use Mockery as m;

/**
 * Class DefaultControllerTest.
 */
class DefaultControllerTest extends BaseFunctionalTest
{
    const FORMAT_DATETIME = 'Y-m-d H:i';

    /**
     * @test
     */
    public function test_main_page()
    {
        $headers = ['CONTENT_TYPE' => 'text/html'];
        $content = ['parameter' => 'value'];

        $crawler = $this->client->request('GET', '/calendar/', [], [], $headers, $content);
        $this->assertResponseCode();
    }

    /**
     * @test
     */
    public function create_event_form_is_rendered_properly()
    {
        $startDate = '200710011200';
        $endDate = '200710011230';

        $crawler = $this->client->request('GET', sprintf('/calendar/occurrence/new?startDate=%s&endDate=%s', $startDate, $endDate));
        $this->assertResponseCode();

        $form = $crawler->selectButton('dende_calendar.form.submit.label')->form();

        $this->assertEquals('2007-10-01 12:00', $form['create_event[startDate]']->getValue());
        $this->assertEquals('2007-10-01 12:30', $form['create_event[endDate]']->getValue());
        $this->assertEquals(30, $form['create_event[duration]']->getValue());
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

        $crawler = $this->client->request('GET', '/calendar/occurrence/' . $occurrenceId);
        $this->assertResponseCode();

        $formElement = $crawler->filter('form[name="update_event"]')->first();
        $this->assertCount(2, $formElement->filter('button'));

        $form = $crawler->selectButton('update_event[delete_event]')->form();
        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals('/calendar/', $this->client->getRequest()->getRequestUri());

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

        $crawler = $this->client->request('GET', '/calendar/occurrence/' . $occurrenceId);
        $this->assertResponseCode();

        $formElement = $crawler->filter('form[name="update_event"]')->first();
        $this->assertCount(3, $formElement->filter('button'));

        $form = $crawler->selectButton('update_event[delete_event]')->form();
        $this->client->submit($form);
        $this->assertResponseCode();
        $this->assertEquals('/calendar/', $this->client->getRequest()->getRequestUri());

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
