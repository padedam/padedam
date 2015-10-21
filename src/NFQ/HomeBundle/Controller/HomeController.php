<?php

namespace NFQ\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction()
    {
        return $this->render('NFQHomeBundle:Home:home.html.twig', array(
                // ...
            ));
    }

    public function signupAction()
    {
        return $this->render('NFQHomeBundle:Home:signup.html.twig', array(
                // ...
            ));
    }

    public function profileAction()
    {
        return $this->render('NFQHomeBundle:Home:profile.html.twig', array(
                // ...
            ));
    }

}
