<?php
namespace Dende\CalendarBundle\Tests\Factory;

use DateTime;
use Dende\Calendar\Domain\Calendar\Event\Occurrence\OccurrenceDuration;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended;
use Dende\Calendar\Application\Factory\OccurrenceFactory as BaseFactory;


class OccurrenceFactory extends BaseFactory
{
    public function createFromArray($array = [])
    {
        $template = [
            'id'             => $this->idGenerator->generateId(),
            'startDate'      => new DateTime('now'),
            'duration'       => new OccurrenceDuration(90),
            'event'          => null,
        ];

        $array = array_merge($template, $array);

        return new OccurrenceExtended(
            $array['id'],
            $array['startDate'],
            new OccurrenceDuration($array['duration']),
            $array['event']
        );
    }
}