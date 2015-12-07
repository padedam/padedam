<?php

namespace NFQ\HomeBundle\Controller;

use Chencha\Pspell\Config;
use Chencha\Pspell\Pspell;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
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

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository("NFQAssistanceBundle:AssistanceRequest");
        $e = $rep->findOneBy([]);
        $e->setStatus(AssistanceRequest::STATUS_WAITING);
        $em->flush();
        dump($e);
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
