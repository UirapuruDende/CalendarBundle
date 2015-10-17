<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;
use Dende\CommonBundle\DataFixtures\BaseFixture;

/**
 * Class CalendarsData
 * @package Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM
 */
final class CalendarsData extends BaseFixture
{
    protected $dir = __DIR__;

    public function getOrder()
    {
        return 0;
    }

    /**
     * @param $params
     * @return Calendar
     */
    public function insert($params)
    {
        return new Calendar(
            new CalendarId($params["id"]),
            $params["name"]
        );
    }
}
