<?php

/**
 * KaikMedia EventsModule
 *
 * @package    KaikmediaEventsModule
 * @author     Kaik <contact@kaikmedia.com>
 * @copyright  KaikMedia
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaEvents.git
 */

namespace Kaikmedia\EventsModule\Controller;

use Kaikmedia\EventsModule\Entity\EventEntity;
use Kaikmedia\EventsModule\Form\Type\EventType;
use Kaikmedia\EventsModule\Form\Type\EventFilterType;
//use Kaikmedia\GalleryModule\Manager\Plugin as GalleryPlugin;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;

/**
 */
class EventController extends AbstractController
{
    /**
     * @Route("")
     *
     * The default entry point.
     * This redirects back to the default entry point for the Users module.
     *
     * @return RedirectResponse
     */
    public function indexAction()
    {
        return new RedirectResponse($this->get('router')->generate('kaikmediaeventsmodule_event_view', [], RouterInterface::ABSOLUTE_URL));
    }

    /**
     * @Route("/view/{page}", requirements={"page" = "\d*"}, defaults={"page" = 1})
     *
     * Display events list.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function viewAction(Request $request, $page)
    {
        // access throw component instance user
        $this->get('kaikmedia_events_module.access_manager')->hasPermission(ACCESS_OVERVIEW, true, '::view');

        $a = [];
        $a['page'] = $page;
        $a['limit'] = $request->query->get('limit', 25);
        $a['online'] = 1; // this should be 1 by default

        $events = $this->getDoctrine()
            ->getManager()
            ->getRepository('Kaikmedia\EventsModule\Entity\EventEntity')
            ->findAll($a);

        return $this->render('KaikmediaEventsModule:User:view.html.twig', [
            'events'    => $events,
            'thisPage' => $a['page'],
            'maxPages' => ceil(count($events) / $a['limit'])
        ]);
    }

    /**
     * @Route("/display/{urltitle}")
     *
     * Display item.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function displayAction(Request $request, $urltitle)
    {
        // access throw component instance user
        $this->get('kaikmedia_events_module.access_manager')->hasPermission(ACCESS_OVERVIEW, true, '::display');

        $a = [];
//        $a['online'] = 1;
        $a['urltitle'] = $urltitle;
//        $a['language'] = $this->container->get('translator')->getLocale();

        $event = $this->getDoctrine()
            ->getManager()
            ->getRepository('Kaikmedia\EventsModule\Entity\EventEntity')
            ->getOneBy($a);

        if (!$event) {
            throw new NotFoundHttpException();
        }

        return $this->render('KaikmediaEventsModule:User:display.html.twig', [
            'event' => $event
        ]);
    }

}
