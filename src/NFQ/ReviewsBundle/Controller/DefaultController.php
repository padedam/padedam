<?php

namespace NFQ\ReviewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NFQReviewsBundle:Default:index.html.twig', array('name' => $name));
    }
}
