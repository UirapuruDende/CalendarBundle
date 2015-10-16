<?php
namespace Dende\CalendarBundle\Tests\Functional\Controller;

use AppKernel;
use PHPUnit_Framework_TestCase;
use Symfony\Component\BrowserKit\Client;

/**
 * Class DefaultControllerTest
 * @package Dende\CalendarBundle\Tests\Functional\Controller
 */
final class DefaultControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $this->client = $kernel->getContainer()->get('test.client');
    }

    public function testItRunsSuccessfully()
    {
        $headers = array('CONTENT_TYPE' => 'text/html');
        $content = array('parameter' => 'value');

        $crawler = $this->client->request('GET', '/calendar/index', [], [], $headers, $content);


        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals("Works!", $this->getContent());
    }

    private function getContent()
    {
        return $this->client->getResponse()->getContent();
    }


    private function getStatusCode()
    {
        return $this->client->getResponse()->getStatusCode();
    }
}