<?php

namespace NFQ\AssistanceBundle\Controller;

use NFQ\AssistanceBundle\Form\AssistanceRequestType;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use ONGR\ElasticsearchBundle\DSL\Query\MatchAllQuery;
use ONGR\ElasticsearchBundle\DSL\Suggester\Term;
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
//        $resp = $this->getAssistanceManager()->getRequestsForMe();
//        foreach($resp['data'] as $req){
//            dump($req->getOwner()->getFirstName());
//            exit;
//        }
        return $this->render('NFQAssistanceBundle:Assistance:requestCategory.html.twig');
    }

    /**
     * @return \NFQ\AssistanceBundle\Service\AssistanceManager
     */
    private function getAssistanceManager()
    {
        return $this->container->get('nfq_assistance.assistance_manager');
    }

    /**
     * @return \NFQ\UserBundle\Service\TagManager
     */
    private function getTagManager(){
        return $this->container->get('nfq_user.tag_manager');
    }

    /**
     * @return JsonResponse
     */
    public function matchTagsAction()
    {
        $response = $this->getTagManager()->findTag();
        return new JsonResponse($response);
    }

    /**
     * @return JsonResponse
     */
    public function suggestTagAction()
    {
        return new JsonResponse($this->getTagManager()->suggestTag());
    }

    /**
     * @return JsonResponse
     */
    public function saveTagsAction()
    {
        $response = $this->getTagManager()->saveTag();
        return new JsonResponse($response);
    }


    /**
     * @return JsonResponse
     */
    public function removeTagsAction()
    {
        $response = $this->getTagManager()->removeTag();
        return new JsonResponse($response);
    }

    /**
     * @return JsonResponse
     */
    public function myTagsAction()
    {
        return new JsonResponse($this->getTagManager()->getMyChildTags($this->getUser()));
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
        $this->get('session')->getFlashBag()->add('danger', 'assistance_not_done');
        return new RedirectResponse($request->server->get('HTTP_REFERER'));
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
        $this->get('session')->getFlashBag()->add('success', 'assistance_registered');
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
        $this->get('session')->getFlashBag()->add('info', 'assistance_helper_cancel');
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
        $this->get('session')->getFlashBag()->add('danger', 'assistance_canceled');
        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }
}
