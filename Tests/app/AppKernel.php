<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 *
 * Only for purpose of tests
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new Dende\CalendarBundle\DendeCalendarBundle(),

            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Liip\FunctionalTestBundle\LiipFunctionalTestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
//            new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new League\Tactician\Bundle\TacticianBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config_' . $this->getEnvironment() . '.yml');
    }

    public function boot()
    {
        parent::boot();

        AnnotationRegistry::registerLoader('class_exists');

        $logger = $this->container->get('logger');
        \Monolog\ErrorHandler::register($logger);
    }
}
