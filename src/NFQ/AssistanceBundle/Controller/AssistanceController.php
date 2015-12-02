<?php

namespace NFQ\AssistanceBundle\Controller;

use NFQ\AssistanceBundle\Form\AssistanceRequestType;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use ONGR\ElasticsearchBundle\DSL\Query\MatchAllQuery;
use ONGR\ElasticsearchBundle\DSL\Suggester\Term;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;

class AssistanceController extends Controller
{

    public function fooAction(Request $request)
    {
        $text = $request->get('text');

        $repository = $this->get('es.manager.default.tag');
        $search = $repository->createSearch();
        $search->addQuery(new MatchAllQuery());
        $termSuggester = new Term('name', $text);
        $termSuggester->setAnalyzer('lithuanian');
        $termSuggester->setSuggestMode(Term::SUGGEST_MODE_POPULAR);
        $search->addSuggester($termSuggester);
        $results = $repository->execute($search);

    }
    
    public function requestFormAction(Request $request)
    {
        $assistanceRequest = new AssistanceRequest();
        $form = $this->createForm(new AssistanceRequestType(), $assistanceRequest, array('method'=>'POST'));

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $currentUser = $this->getUser();
            $assistanceRequest->setOwner($currentUser);

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


    public function getParentTagsAction()
    {

    }


}
