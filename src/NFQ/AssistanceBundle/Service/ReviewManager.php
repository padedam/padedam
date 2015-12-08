<?php

namespace NFQ\AssistanceBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ReviewManager
 * @package NFQ\AssistanceBundle\Service
 */
class ReviewManager
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
     * ReviewManager constructor.
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
     * @return array
     */
    public function getReviewList()
    {
        $currentUser  = $this->getUser();
        $thank = $this->em->getRepository('NFQReviewsBundle:Thanks')->findOneByUser($currentUser);

        if (!$thank) {
            $result = ['number' => 0, 'reviews' => []];
        } else {
            $result = ['number' => $thank->getNumber(), 'reviews' => $currentUser->getGReviews()->toArray()];
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getHeroesList()
    {
        $reviewRepository = $this->em->getRepository('NFQReviewsBundle:Thanks');
        return $reviewRepository->getTopHelpers();
    }

    /**
     * @return mixed
     * @throws AccessDeniedException
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