<?php
namespace Dende\CalendarBundle\Tests\Entity;

use Dende\Calendar\Domain\Calendar\Event\Occurrence as BaseOccurrence;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Occurrence
 * @package Dende\CalendarBundle\Tests\Entity
 * @ORM\Entity()
 * @ORM\Table(name="occurrences")
 */
class Occurrence extends BaseOccurrence
{

}