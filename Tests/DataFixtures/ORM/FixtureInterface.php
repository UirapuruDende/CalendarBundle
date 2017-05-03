<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\ORM;

interface FixtureInterface
{
    public function insert(array $params = []);
}
