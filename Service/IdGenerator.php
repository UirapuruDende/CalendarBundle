<?php
namespace Dende\CalendarBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\UuidGenerator;

/**
 * Class IdGenerator
 * @package Dende\CalendarBundle\Service
 */
final class IdGenerator extends UuidGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * IdGenerator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return bool|mixed|string
     */
    public function generateId()
    {
        return parent::generate($this->em, null);
    }
}