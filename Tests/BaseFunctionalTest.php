<?php
namespace Dende\CalendarBundle\Tests;

use Dende\CalendarBundle\Tests\DataFixtures\ORM\CalendarsData;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\EventsData;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\OccurrencesData;
use Dende\CommonBundle\Tests\BaseFunctionalTest as BaseTest;

/**
 * Class BaseFunctionalTest
 * @package Dende\CalendarBundle\Tests
 */
abstract class BaseFunctionalTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
        $this->loadFixtures([
            CalendarsData::class,
            EventsData::class,
            OccurrencesData::class
        ], 'default');
    }
}
