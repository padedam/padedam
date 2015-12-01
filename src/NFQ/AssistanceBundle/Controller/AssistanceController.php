<?php

namespace NFQ\AssistanceBundle\Controller;

use NFQ\AssistanceBundle\Form\AssistanceRequestType;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use NFQ\AssistanceBundle\Entity\Tags;
use NFQ\AssistanceBundle\Entity\Tag2User;
use NFQ\ReviewsBundle\Entity\Review;
use NFQ\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;

class AssistanceController extends Controller
{
    public function requestFormAction(Request $request)
    {
        $assistanceRequest = new AssistanceRequest();
        $form = $this->createForm(new AssistanceRequestType(), $assistanceRequest, array('method'=>'POST'));

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $currentUser = $this->getUser();
            $assistanceRequest->setOwner($currentUser);
            $assistanceRequest->setStatus(AssistanceRequest::STATUS_WAITING);

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

        $allAssistanceRequests = $this->getDoctrine()->getRepository('NFQAssistanceBundle:AssistanceRequest')->findAll();
        $myAssistanceRequests = [];
        $waitingRequests = [];
        $takenRequests = [];

        foreach($allAssistanceRequests as $request){
            if($request->getOwner()==$this->getUser()){
                $myAssistanceRequests[] = $request;
            }
            elseif($request->getStatus()==AssistanceRequest::STATUS_TAKEN){
                if($request->getHelper()==$this->getUser()){
                    $takenRequests[] = $request;
                }
            }
            elseif($request->getStatus()==AssistanceRequest::STATUS_WAITING){
                $waitingRequests[] = $request;
            }

        }

        return $this->render('NFQAssistanceBundle:Assistance:requestList.html.twig', array('waitingRequests'=>$waitingRequests,
            'myAssistanceRequests'=>$myAssistanceRequests, 'takenRequests'=>$takenRequests));
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
     * @return JsonResponse
     */
    public function removeTagsAction()
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

    public function notDoneAction(Request $request, $arid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $assistanceRequest = $em->getRepository('NFQAssistanceBundle:AssistanceRequest')->find($arid);

        if($assistanceRequest->getOwner()!=$currentUser ||
            $assistanceRequest->getStatus()!=AssistanceRequest::STATUS_TAKEN){
            throw new Exception('problems');
        }

        $assistanceRequest->setStatus(AssistanceRequest::STATUS_WAITING);
        $assistanceRequest->setHelper(null);

        $em->persist($assistanceRequest);
        $em->flush();

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }


    public function getParentTagsAction()
    {

    }

    public function helpAction(Request $request, $arid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $assistanceRequest = $em->getRepository('NFQAssistanceBundle:AssistanceRequest')->find($arid);

        if($assistanceRequest->getOwner()==$currentUser ||
            $assistanceRequest->getStatus()!=AssistanceRequest::STATUS_WAITING){
            throw new Exception('problems');
        }

        $assistanceRequest->setStatus(AssistanceRequest::STATUS_TAKEN);
        $assistanceRequest->setHelper($currentUser);

        $em->persist($assistanceRequest);
        $em->flush();

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }

    public function helperCancelAction(Request $request, $arid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $assistanceRequest = $em->getRepository('NFQAssistanceBundle:AssistanceRequest')->find($arid);

        if($assistanceRequest->getHelper()!=$currentUser ||
            $assistanceRequest->getStatus()!=AssistanceRequest::STATUS_TAKEN){
            throw new Exception('problems');
        }

        $assistanceRequest->setStatus(AssistanceRequest::STATUS_WAITING);
        $assistanceRequest->setHelper(null);

        $em->persist($assistanceRequest);
        $em->flush();

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }


    public function cancelAction(Request $request, $arid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $assistanceRequest = $em->getRepository('NFQAssistanceBundle:AssistanceRequest')->find($arid);

        if($assistanceRequest->getOwner()!=$currentUser){
            throw new Exception('problems');
        }

        $assistanceRequest->setStatus(AssistanceRequest::STATUS_CANCELED);
        $assistanceRequest->setHelper(null);

        $em->persist($assistanceRequest);
        $em->flush();

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }
}
