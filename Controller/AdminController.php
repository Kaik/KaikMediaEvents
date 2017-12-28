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

use Kaikmedia\EventsModule\Form\Type\SettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Core\Controller\AbstractController;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/index")
     *
     * @Theme("admin")
     *
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function indexAction()
    {
        // access throw component instance user
        $this->get('kaikmedia_events_module.access_manager')->hasPermission(ACCESS_ADMIN, true);

        return new RedirectResponse($this->get('router')->generate('kaikmediaeventsmodule_manager_list', [], RouterInterface::ABSOLUTE_URL));
    }

    /**
     * @Route("/preferences")
     *
     * @Theme("admin")
     *
     * @return Response symfony response object
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function preferencesAction(Request $request)
    {
        // access throw component instance user
        $this->get('kaikmedia_events_module.access_manager')->hasPermission(ACCESS_ADMIN, true);

        $form = $this->createForm(SettingsType::class, $settings = [], [
            'action' => $this->get('router')->generate('kaikmediaeventsmodule_admin_preferences', [], RouterInterface::ABSOLUTE_URL),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            foreach ($data as $key => $value) {
            }
        }

        return $this->render('@KaikmediaEventsModule/Admin/preferences.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
