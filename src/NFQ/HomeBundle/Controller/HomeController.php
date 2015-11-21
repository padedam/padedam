<?php

namespace NFQ\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function signupAction()
    {
        return $this->render('NFQHomeBundle:Home:signup.html.twig', array(
                // ...
            ));
    }
    public function homeAction()
    {
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

    /**
    * @return \NFQ\AssistanceBundle\Service\AssistanceManager
    */
    private function getAssistanceManager()
    {
        return $this->container->get('nfq_assistance.assistance_manager');
    }
}
