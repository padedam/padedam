<?php

namespace NFQ\AssistanceBundle\Controller;

use NFQ\AssistanceBundle\Form\AssistanceRequestType;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use NFQ\AssistanceBundle\Entity\AssistanceCategories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AssistanceController extends Controller
{
    public function requestFormAction(Request $request)
    {
        $assistanceRequest = new AssistanceRequest();
        $form = $this->createForm(new AssistanceRequestType(),$assistanceRequest, array('method'=>'POST'));

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $shortDescription = $form->get('shortDescription')->getData();
            $longDescription = $form->get('longDescription')->getData();

            $assistanceRequest->setShortDescription($shortDescription);
            $assistanceRequest->setLongDescription($longDescription);

            $em = $this->getDoctrine()->getManager();
            $em->persist($assistanceRequest);
            $em->flush();

            return $this->redirectToRoute('nfq_assistance_request_submitted');
        }

        return $this->render('NFQAssistanceBundle:Assistance:requestForm.html.twig', array('form' => $form->createView()));
    }

    public function requestSubmittedAction(){
        return $this->render('NFQAssistanceBundle:Assistance:requestSubmitted.html.twig');
    }

    public function requestListAction(){

        $assistance = $this->getDoctrine()->getRepository('NFQAssistanceBundle:AssistanceRequest')->findAll();

        return $this->render('NFQAssistanceBundle:Assistance:requestList.html.twig', array('assistance'=>$assistance));
    }

    public function requestCategoryAction(){
//create categories
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('NFQAssistanceBundle:AssistanceCategories');

        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>'
        );

        $htmlTree = $repo->childrenHierarchy(
            null, /* starting from root nodes */
            false, /* true: load all children, false: only direct */
            $options
        );

        //dump($category); exit;
        return $this->render('NFQAssistanceBundle:Assistance:requestCategory.html.twig', array('tree'=>$htmlTree));
    }
}
