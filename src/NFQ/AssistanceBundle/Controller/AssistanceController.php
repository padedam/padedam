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

        $tag = $request->query->get('tag', '');
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('t.id AS id, t.title as text')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->where('t.title LIKE :title')
            ->setParameter(':title', $tag . '%');
        $tags = $qb->getQuery()->getArrayResult();

        return new JsonResponse($tags);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTagsAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $tag_ar = $request->request->get('tag', null);
        if(!is_array($tag_ar)) exit('no info');

        if($tag_id = $tag_ar['id'] and !is_numeric($tag_id)){
            //create a new tag
            $tag = new Tags();
            $tag->setTitle($tag_id);
            $em->persist($tag);
            $em->flush();
        }else{
            $tagRepo = $this->getDoctrine()->getRepository('NFQAssistanceBundle:Tags');
            $tag = $tagRepo->findOneById($tag_id);
        }

        $user = $this->getUser();

        //check if not present already
        $tagRepo = $this->getDoctrine()->getRepository('NFQAssistanceBundle:Tag2User');
        $tag2userCheck = $tagRepo->findOneBy(['tag'=>$tag, 'user'=>$user]);
        if(!empty($tag2userCheck)){
            exit('already present');
        }

        $tag2user = new Tag2User();
        $tag2user->setTag($tag);
        $tag2user->setUser($user);

        $em  = $this->getDoctrine()->getManager();
        $em->persist($tag2user);
        $em->flush();

        return new JsonResponse('saved');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeTagsAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $tagAr = $request->request->get('tag', null);
        if(!isset($tagAr['id'])){
            exit('no tag sent');
        }else{
            $tag_id = $tagAr['id'];
        }
        $tagRepo = $this->getDoctrine()->getRepository('NFQAssistanceBundle:Tags');
        if(!is_numeric($tag_id)){
            $tag = $tagRepo->findOneByTitle($tag_id);
        }else{
            $tag = $tagRepo->findOneById($tag_id);
        }
        if(!$tag){
            exit('this tag was not found');
        }

        $user = $this->getUser();
        $tag2userRepo = $this->getDoctrine()->getRepository('NFQAssistanceBundle:Tag2User');
        $tag2user = $tag2userRepo->findOneBy(['user'=>$user, 'tag'=>$tag]);

        if(!$tag2user){
            exit('user does not have this tag');
        }

        $em  = $this->getDoctrine()->getManager();
        $em->remove($tag2user);
        $em->flush();

        return new JsonResponse('removed');
    }

    /**
     * @return JsonResponse
     */
    public function myTagsAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $dql = '
            SELECT   t.id AS id, t.title as text
            FROM     NFQAssistanceBundle:Tags t
            JOIN     t.usersWithTag t2u
            WHERE    t2u.user = :user
        ';
        $tags = $em->createQuery($dql)->setParameter('user', $this->getUser())->getResult();

        return new JsonResponse($tags);
    }
}
