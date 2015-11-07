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
        return $this->render('NFQHomeBundle:Home:home.html.twig', array(
                // ...
            ));
    }

}
