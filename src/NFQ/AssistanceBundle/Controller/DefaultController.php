<?php

namespace NFQ\AssistanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NFQAssistanceBundle:Default:index.html.twig', array());
    }
}
