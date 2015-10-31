<?php
namespace Dende\CalendarBundle\Entity;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;

/**
 * Class CalendarFactory
 * @package Gyman\Domain
 * @TODO: Move to Calendar library !!!!
 */
final class CalendarFactory
{
    /**
     * @param $params
     * @return Calendar
     */
    public static function createFromArray($array)
    {
        $template = [
            'id'                     => '',
            'title'                  => '',
        ];

        $array = array_merge($template, $array);

        return new Calendar(
            $array['id'],
            $array['title']
        );
    }

    /**
     * @return Calendar
     */
    public static function create()
    {
        return self::createFromArray([]);
    }
}
