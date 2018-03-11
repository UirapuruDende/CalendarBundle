<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\ORM;

use Dende\Calendar\Domain\Calendar;
use Dende\CalendarBundle\Tests\DataFixtures\BaseFixture;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CalendarsData.
 */
final class CalendarsData extends BaseFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /** @var string */
    protected $dir = __DIR__;

    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param $params
     *
     * @return Calendar
     */
    public function insert($params)
    {
        return new Calendar(Uuid::fromString($params['id']), $params['name']);
    }
}
