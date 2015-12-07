<?php

namespace NFQ\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends Controller
{

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->container->get('router')->generate('nfq_assistance_request_list', []));
        }

        return $this->render('NFQHomeBundle:Home:home.html.twig', [
            'lastUsers' => $this->getUserManager()->getLastUsers(),
        ]);
    }

    /**
     * @return \NFQ\UserBundle\Service\UserManager
     */
    private function getUserManager()
    {
        return $this->container->get('nfq_user.user_manager');
    }


}
