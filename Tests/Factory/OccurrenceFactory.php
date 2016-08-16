<?php
namespace Dende\CalendarBundle\Tests\Factory;

use DateTime;
use Dende\Calendar\Domain\Calendar\Event\Duration;
use Dende\CalendarBundle\Tests\Entity\Occurrence;

class OccurrenceFactory extends \Dende\Calendar\Application\Factory\OccurrenceFactory
{
    public function createFromArray($array = [])
    {
        $template = [
            'id'             => $this->idGenerator->generateId(),
            'startDate'      => new DateTime('now'),
            'duration'       => new Duration(90),
            'event'          => null,
        ];

        $array = array_merge($template, $array);

        return new Occurrence(
            $array['id'],
            $array['startDate'],
            $array['duration'],
            $array['event']
        );
    }
}