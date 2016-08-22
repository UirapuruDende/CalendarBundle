<?php
namespace Dende\CalendarBundle\Tests;

use Dende\CalendarBundle\Tests\DataFixtures\ORM\CalendarsData;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\EventsData;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\OccurrencesData;
use Dende\CommonBundle\Tests\BaseFunctionalTest as BaseTest;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Driver\Connection;

/**
 * Class BaseFunctionalTest
 * @package Dende\CalendarBundle\Tests
 */
abstract class BaseFunctionalTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        /** @var Connection $conn */
        $conn = $this->getContainer()->get("doctrine.orm.default_entity_manager")->getConnection();

        $conn->exec("SET foreign_key_checks = 0");

        $this->loadFixtures([
            CalendarsData::class,
            EventsData::class,
            OccurrencesData::class
        ], 'default', 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE);

        $conn->exec("SET foreign_key_checks = 1");


    }
}
