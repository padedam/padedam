<?php

namespace NFQ\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction()
    {
        return $this->render('NFQHomeBundle:Home:home.html.twig', array(
                // ...
            ));    }

}
