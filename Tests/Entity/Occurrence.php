<?php
namespace Dende\CalendarBundle\Tests\Entity;

use Dende\Calendar\Domain\Calendar\Event\Occurrence as BaseOccurrence;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Occurrence
 * @package Dende\CalendarBundle\Tests\Entity
 * @ORM\Entity()
 * @ORM\Table(name="occurrences")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Occurrence extends BaseOccurrence
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $testField = '123';
}