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

        exit('ok');


        /*  $stat = new Tags();
          $stat->setTitle('Statyba');

          $fruits = new Tags();
          $fruits->setTitle('Dažyti');
          $fruits->setParent($stat);

          $vegetables = new Tags();
          $vegetables->setTitle('Kalti');
          $vegetables->setParent($stat);

          $carrots = new Tags();
          $carrots->setTitle('Cementas');
          $carrots->setParent($stat);

          $em  = $this->getDoctrine()->getManager();
          $em->persist($stat);
          $em->persist($fruits);
          $em->persist($vegetables);
          $em->persist($carrots);
          $em->flush();


          exit('ok');*/
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
     * @param Request $request
     * @return JsonResponse
     */
    public function matchTagsAction(Request $request)
    {
        $tag = $request->request->get('tag');
        $tag = 'ce';
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('t.id AS id, t.title as text')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->where('t.title LIKE :title')
            ->setParameter(':title', $tag . '%');
        $tags = $qb->getQuery()->getArrayResult();

        return new JsonResponse(array('tags' => $tags));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTagsAction(Request $request)
    {
        $tagId = $request->request->get('tag', null);
        $userId = $request->request->get('user', null);

        if(!is_numeric($tagId) or !is_numeric($userId)){
            throw $this->createNotFoundException(
                "Some data is missing"
            );
        }
        $userRepository = $this->getDoctrine()->getRepository('NFQUserBundle:User');
        $tagRepository = $this->getDoctrine()->getRepository('NFQAssistanceBundle:Tags');

        $user =$userRepository->findOneById($userId);
        if (!$user) {
            throw $this->createNotFoundException(
                "No user with id $userId found"
            );
        }
        $tag =$tagRepository->findOneById($tagId);
        if (!$tag) {
            throw $this->createNotFoundException(
                "No tag with id $tagId found"
            );
        }

        $tag2user = new Tag2User();
        $tag2user->setTag($tag);
        $tag2user->setUser($user);

        $em  = $this->getDoctrine()->getManager();
        $em->persist($tag2user);
        $em->flush();

        return new JsonResponse(array('response' => $tag2user));
    }
}
