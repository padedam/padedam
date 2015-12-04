<?php

namespace NFQ\AssistanceBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use NFQ\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AssistanceManager
 * @package NFQ\AssistanceBundle\Service
 */
class AssistanceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var User
     */
    private $user;

    /**
     * AssistanceManager constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $requestStack
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em,
                                RequestStack $requestStack,
                                AuthorizationCheckerInterface $authorizationChecker,
                                TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return string
     */
    public function getMyRequests()
    {
        $response = [];
        try{
            $repo = $this->em->getRepository('NFQAssistanceBundle:AssistanceRequest');
            $results = $repo->getMyRequests($this->getUser());
            $response['status'] = 'success';
            $response['data'] = $results;
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['data'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return array
     */
    public function getRequestsForMe()
    {
        $response = [];
        try{
            $repo = $this->em->getRepository('NFQAssistanceBundle:AssistanceRequest');

            //get all my tags
            $tagsRepo = $this->em->getRepository('NFQAssistanceBundle:Tags');
            $myTags = $tagsRepo->getMyRootTags($this->getUser());
            $tagAr = [];
            foreach($myTags as $tag){
                $tagAr[] = $tag['id'];
            }
            if( empty($tagAr) ){
                throw new \Exception ('you have not defined any tags');
            }

            $results = $repo->getRequestsForMe($this->getUser(), $tagAr);

            $response['status'] = 'success';
            $response['data'] = $results;
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['data'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return array
     */
    public function getMyTakenRequests()
    {
        $response = [];
        try{

            $repo = $this->em->getRepository('NFQAssistanceBundle:AssistanceRequest');

            $results = $repo->getMyTakenRequests($this->getUser());

            $response['status'] = 'success';
            $response['data'] = $results;
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return string
     */
    public function getRequestsFrontPage()
    {
        $repo = $this->em->getRepository('NFQAssistanceBundle:AssistanceRequest');
        return $repo->getRequestsFrontPage();
    }

    /**
     * @return mixed
     */
    private function getUser()
    {
        if ( !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') ) {
            throw new AccessDeniedException();
        }else {
            return $this->tokenStorage->getToken()->getUser();
        }
    }


}