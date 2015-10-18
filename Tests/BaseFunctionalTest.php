<?php
namespace Dende\CalendarBundle\Tests;

use Dende\CommonBundle\Tests\BaseFunctionalTest as BaseTest;

abstract class BaseFunctionalTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([
            "Dende\\CalendarBundle\\Tests\\DataFixtures\\Standard\\ORM\\CalendarsData",
            "Dende\\CalendarBundle\\Tests\\DataFixtures\\Standard\\ORM\\EventsData",
            "Dende\\CalendarBundle\\Tests\\DataFixtures\\Standard\\ORM\\OccurrencesData"
        ], 'sqlite');

    }
}
