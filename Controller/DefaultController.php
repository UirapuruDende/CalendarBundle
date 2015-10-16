<?php
namespace Dende\CalendarBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Dende\CalendarBundle\Controller
 */
final class DefaultController extends Controller
{
    /**
     * @Template()
     * @return Response
     */
    public function indexAction()
    {
        return [];
    }
}
