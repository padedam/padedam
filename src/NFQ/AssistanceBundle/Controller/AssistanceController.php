<?php

namespace NFQ\AssistanceBundle\Controller;

use NFQ\AssistanceBundle\Form\AssistanceRequestType;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use NFQ\AssistanceBundle\Entity\Tags;
use NFQ\AssistanceBundle\Entity\Tag2User;
use NFQ\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;

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

    public function requestCategoryAction()
    {

        $htmlTree = $this->getAssistanceManager()->getCategoryTree();
        return $this->render('NFQAssistanceBundle:Assistance:requestCategory.html.twig', array('tree'=>$htmlTree));
    }

    /**
     * @return \NFQ\AssistanceBundle\Service\AssistanceManager
     */
    private function getAssistanceManager()
    {
        return $this->container->get('nfq_assistance.assistance_manager');
    }

    /**
     * @return JsonResponse
     */
    public function matchTagsAction()
    {
        $container = $this->container->get('nfq_user.tag_manager');
        $response = $container->findTag();
        return new JsonResponse($response);
    }

    /**
     * @return JsonResponse
     */
    public function saveTagsAction()
    {
        $container = $this->container->get('nfq_user.tag_manager');
        $response = $container->saveTag($this->getUser());
        return new JsonResponse($response);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeTagsAction(Request $request)
    {
        $container = $this->container->get('nfq_user.tag_manager');
        $response = $container->removeTag();
        return new JsonResponse($response);
    }

    /**
     * @return JsonResponse
     */
    public function myTagsAction()
    {
        $tagService = $this->container->get('nfq_user.tag_manager');
        return new JsonResponse($tagService->getMyChildTags($this->getUser()));
    }
}
