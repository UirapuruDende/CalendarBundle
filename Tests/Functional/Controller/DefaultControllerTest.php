<?php
namespace Dende\CalendarBundle\Tests\Functional\Controller;

use AppKernel;
use Dende\CalendarBundle\Tests\BaseFunctionalTest;
use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class DefaultControllerTest
 * @package Dende\CalendarBundle\Tests\Functional\Controller
 */
final class DefaultControllerTest extends BaseFunctionalTest
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testItRunsSuccessfully()
    {
        $headers = array('CONTENT_TYPE' => 'text/html');
        $content = array('parameter' => 'value');

//        $crawler = $this->client->request('GET', '/calendar/index', [], [], $headers, $content);
//
//        $this->assertEquals(200, $this->getStatusCode());
//        $this->assertEquals("Works!", $this->getContent());
    }
}
