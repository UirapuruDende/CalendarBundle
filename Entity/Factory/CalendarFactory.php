<?php
namespace Dende\CalendarBundle\Entity\Factory;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;
use Dende\CalendarBundle\Service\IdGenerator;
use Dende\CalendarBundle\Entity\CalendarFactory as BaseCalendarFactory;

/**
 * Class CalendarFactory
 * @package Dende\CalendarBundle\Entity\Factory
 */
final class CalendarFactory
{
    /**
     * @var IdGenerator
     */
    private $idGenerator;

    /**
     * CalendarFactory constructor.
     * @param $idGenerator
     */
    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function createFromArray($array)
    {
        $newId = array_key_exists("id", $array) ? $array["id"] : $this->idGenerator->generateId();

        return BaseCalendarFactory::createFromArray([
            "id" => $newId,
            "title" => $array["title"]
        ]);
    }
}
