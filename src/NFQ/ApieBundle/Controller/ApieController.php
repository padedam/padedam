<?php

namespace NFQ\ApieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApieController extends Controller
{
    public function apieAction()
    {
        return $this->render('NFQApieBundle:Apie:apie.html.twig', array("apie" => "Padedam, nes galim!!!"));
    }
}
