<?php
namespace Dende\CalendarBundle\Tests\Entity;

use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;

class OccurrenceExtended extends Occurrence implements OccurrenceInterface
{
    /**
     * @var string
     */
    protected $test = 'just a test to prove working inheritance';
}
