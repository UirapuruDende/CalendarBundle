<?php
namespace Dende\CalendarBundle\Tests\Entity;

use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class OccurrenceExtended extends Occurrence
{
    /**
     * @var string
     */
    protected $test = 'just a test to prove working inheritance';
}