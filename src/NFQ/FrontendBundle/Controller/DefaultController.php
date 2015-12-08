<?php

namespace NFQ\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name='anonymous')
    {
        return $this->render('NFQFrontendBundle:Default:index.html.twig', array('name' => $name));
    }
}
