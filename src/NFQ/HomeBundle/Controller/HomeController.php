<?php

namespace NFQ\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends Controller
{

    public function homeAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->container->get('router')->generate('nfq_assistance_request_list', []));
        }

        return $this->render('NFQHomeBundle:Home:home.html.twig', [
            'lastUsers' => $this->getUserManager()->getLastUsers(),
        ]);
    }

    private function getUserManager()
    {
        return $this->container->get('nfq_user.user_manager');
    }

}
