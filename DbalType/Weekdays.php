<?php
namespace Dende\CalendarBundle\DbalType;

use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Weekdays extends Type
{
    private $template = [
        Repetitions::MONDAY    => 'monday',
        Repetitions::TUESDAY   => 'tuesday',
        Repetitions::WEDNESDAY => 'wednesday',
        Repetitions::THURSDAY  => 'thursday',
        Repetitions::FRIDAY    => 'friday',
        Repetitions::SATURDAY  => 'saturday',
        Repetitions::SUNDAY    => 'sunday',
    ];

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'Weekdays';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        die(var_dump($value));
        $days = array_map();

        return new Repetitions($days);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        die(var_dump($value));

        return $value->toDecimal();
    }

    public function getName()
    {
        return 'weekdays';
    }
}
