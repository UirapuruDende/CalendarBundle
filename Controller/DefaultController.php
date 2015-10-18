<?php
namespace Dende\CalendarBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Dende\CalendarBundle\Controller
 * @Route("/calendar")
 */
final class DefaultController extends Controller
{
    /**
     * @Template()
     * @Route
     * @return Response
     */
    public function indexAction()
    {
        return [];
    }
}
