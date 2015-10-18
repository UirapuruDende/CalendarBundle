<?php
namespace Dende\CalendarBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestController
 * @package Dende\CalendarBundle\Controller
 */
final class RestController extends FOSRestController
{

    /**
     * @Rest\View()
     *
     * @Rest\Route("calendar")
     * @Method({"GET"})
     * @return View
     */
    public function getCalendarsAction()
    {
        $calendars = $this->getDoctrine()->getRepository("Calendar:Calendar")->findAll();

        return $this->handleView($this->createView($calendars, 200));
    }

    /**
     * @param mixed  $data
     * @param int    $statusCode
     * @param string $format
     *
     * @return View
     */
    protected function createView($data, $statusCode, $format = 'json')
    {
        $serializationContext = SerializationContext::create()
            ->setSerializeNull(true)
            ->enableMaxDepthChecks()
        ;
        $view = View::create();
        $view->setData($data);
        $view->setStatusCode($statusCode);
        $view->setFormat($format);
        $view->setSerializationContext($serializationContext);
        return $view;
    }
}
