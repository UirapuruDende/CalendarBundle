<?php
namespace Dende\CalendarBundle\Tests\Factory;

use DateTime;
use Dende\Calendar\Application\Factory\OccurrenceFactory as BaseFactory;
use Dende\Calendar\Domain\Calendar\Event\Occurrence\OccurrenceId;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;
use Dende\CalendarBundle\Tests\Entity\OccurrenceExtended;

class OccurrenceFactory extends BaseFactory
{
    public function createFromArray(array $array = []) : OccurrenceInterface
    {
        $template = [
            'occurrenceId' => OccurrenceId::create(),
            'event'        => null,
            'startDate'    => new DateTime(),
            'duration'     => null,
        ];

        $array = array_merge($template, $array);

        return new OccurrenceExtended(
            $array['occurrenceId'],
            $array['event'],
            $array['startDate'],
            $array['duration']
        );
    }
}
