<?php
namespace Dende\CalendarBundle\Tests;

use AppKernel;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\CalendarsData;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\EventsData;
use Dende\CalendarBundle\Tests\DataFixtures\ORM\OccurrencesData;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseTest;
use Exception;
use Mockery;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class BaseFunctionalTest
 * @package Dende\CalendarBundle\Tests
 */
abstract class BaseFunctionalTest extends BaseTest
{
    const FORMAT_DATETIME = "Y-m-d H:i";

    /**
     * @var Client
     */
    protected $client;

    /** @var  Container */
    protected $container;

    /** @var  EntityManager */
    protected $em;

    /** @var  ReferenceRepository */
    protected $fixtures;

    public function setUp()
    {
        $this->client = $this->getClient();
        $this->container = $this->client->getContainer();

        $fixtures = $this->loadFixtures([
            CalendarsData::class,
            EventsData::class,
            OccurrencesData::class
        ], 'default');

        $this->fixtures = $fixtures->getReferenceRepository();

        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @param  array $options
     * @param  array $server
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function getClient(array $options = array(), array $server = array())
    {
        static::$kernel = new AppKernel(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
        static::$kernel->boot();
        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);
        $client->followRedirects(true);
        return $client;
    }

    /**
     * @param int $expectedCode
     */
    public function assertResponseCode($expectedCode = 200)
    {
        $msg = '';
        $code = $this->client->getResponse()->getStatusCode();
        if($code === 500 && ($paragraph = $this->client->getCrawler()->filter("div.text-exception h1")) && $paragraph->count() > 0) {
            $msg.= $this->client->getCrawler()->filter("div#traces-text")->text();
        }
        $this->assertEquals($expectedCode, $code, $msg);
    }

    public function assertFormHasNoErrors($form)
    {
        $this->assertCountFormErrors(0, $form);
    }
    /**
     * @param int $expectedCount
     * @param string $form
     * @throws Exception
     */
    public function assertCountFormErrors($expectedCount = 1, $form)
    {
        if($profiler = $this->client->getProfile()) {
            $data = $profiler->getCollector('form')->getData();

            if(!array_key_exists($form, $data['forms'])) {
                return;
            }

            $formErrors = [];
            $formErrorsCount = 0;

            if(array_key_exists('errors', $data['forms'][$form])) {
                $formErrors = array_column($data['forms'][$form]['errors'], 'message');
                $formErrorsCount = count($data['forms'][$form]['errors']);
            }

            $childrenErrorsCount = array_filter(array_map(function(array $child) {
                return array_key_exists('errors', $child) ? count($child['errors']) : 0;
            }, $data['forms'][$form]['children']));

            $childrenErrorsMessages = array_filter(array_map(function(array $child) {
                return array_key_exists('errors', $child) ? array_column($child['errors'], 'message') : null;
            }, $data['forms'][$form]['children']));

            $errorsMap = array_map(function($fieldErrorMessages, $key){
                return sprintf('%s: "%s"', $key, implode('", "', $fieldErrorMessages));
            }, $childrenErrorsMessages, array_keys($childrenErrorsMessages));

            $erroredFields = array_keys(array_filter($childrenErrorsCount));
            $sum = array_sum($childrenErrorsCount) + $formErrorsCount;
            $childrenErrorsSum = array_sum($childrenErrorsCount);
            $msg = sprintf(
                "\n Array should contain %d errors but found %d.\n Errored children fields: %d with %d errors (%s)\n Main form [%s]: %d errors (\"%s\"). \n %s\n\n",
                $expectedCount,
                $sum,
                count($erroredFields),
                $childrenErrorsSum,
                implode(', ', $erroredFields),
                $form,
                $formErrorsCount,
                implode('", "', $formErrors),
                implode('', $errorsMap)
            );
            $this->assertEquals($expectedCount, $sum, $msg);
        }
    }
    /**
     * @param array $messages
     */
    public function assertValidationMessage(array $messages = [], $form)
    {
        if($profiler = $this->client->getProfile()) {
            $data = $profiler->getCollector('form')->getData();
            foreach($messages as $fieldMessagePair) {
                list($field, $message, $iShouldSeeValidationMessage) = $fieldMessagePair;
                $errors = $data['forms'][$form]['children'][$field]['errors'];
                $errorMessages = array_column($errors, 'message');
                if($iShouldSeeValidationMessage) {
                    $assertError = sprintf("Validation error '%s' was not found for field '%s'. Only errors found: '%s'", $message, $field, implode(', ', $errorMessages));
                    $this->assertTrue(in_array($message, $errorMessages, true), $assertError);
                } else {
                    $assertError = sprintf("Validation error '%s' was found for field '%s' and that's wrong!!!", $message, $field);
                    $this->assertNotTrue(in_array($message, $errorMessages, true), $assertError);
                }
            }
        }
    }
}
