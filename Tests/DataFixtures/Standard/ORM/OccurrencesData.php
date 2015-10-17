<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM;

use DateTime;
use Dende\Calendar\Application\Factory\OccurrenceFactory;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\Duration;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\CommonBundle\DataFixtures\BaseFixture;

/**
 * Class OccurrencesData
 * @package Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM
 */
final class OccurrencesData extends BaseFixture
{
    protected $dir = __DIR__;

    public function getOrder()
    {
        return 20;
    }

    /**
     * @param $params
     * @return Occurrence
     */
    public function insert($params)
    {
        $occurrence = OccurrenceFactory::createFromArray([
            'startDate' => new DateTime($params["startDate"]),
            'duration'  => new Duration($params["duration"]),
            'event'     => $this->getReference($params["event"]),
        ]);
                
        return $occurrence;
    }
}
