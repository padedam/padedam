<?php

namespace NFQ\UserBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use NFQ\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use NFQ\AssistanceBundle\Entity\Tags;
use NFQ\AssistanceBundle\Entity\Tag2User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TagManager
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
     * @var TokenStorage
     */
    private $tokenStorage;


    /**
     * TagManager constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $requestStack
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorage $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage)
    {
        $this->em = $em;

        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User $user
     */
    public function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getMyTags()
    {
        $tagsRepo = $this->em->getRepository('NFQAssistanceBundle:Tags');

        $user = $this->getUser();

        $myRootTags = $tagsRepo->getMyRootTags($user);
        $allRootTags = $tagsRepo->getAllRootTags();
        $mytagsId = [];
        foreach($myRootTags as $k=>$tag){
            $mytagsId[] = $tag['id'];
        }
        foreach($allRootTags as $k=>$tag){
            if(in_array($tag['id'], $mytagsId)) {
                unset($allRootTags[$k]);
            }
        }

        return array('my'=>$myRootTags, 'root'=>$allRootTags);

    }

    /**
     * @return array $response
     */
    public function getMyChildTags(){

        $response = [];
        try {
            if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new AccessDeniedException();
            }
            $user = $this->getUser();

            $parent_id = $this->getRequest()->query->get('parent_id');
            //get parent tag
            $parentTag = $this->em->getRepository('NFQAssistanceBundle:Tags')->findOneById($parent_id);

            $tags = $this->em->getRepository('NFQAssistanceBundle:Tags')->getMyTags($user, $parentTag);

            $response['status'] = 'success';
            $response['tags'] = $tags;
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;

    }

    /**
     * @return array
     */
    public function saveTag(){
        $response = [];
        try {
            if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new AccessDeniedException();
            }

            $tag_ar = $this->getRequest()->get('tag', null);
            $parent_id = $this->getRequest()->get('parent_id', null);

            $user = $this->getUser();
            $tagRepo = $this->em->getRepository('NFQAssistanceBundle:Tags');

            //get parent tag object
            if (is_numeric($parent_id)) {
                $parent = $tagRepo->findOneBy(array('id' => $parent_id));
            }else{
                $parent = null;
            }

            if (!is_array($tag_ar)) {
                throw new \InvalidArgumentException('Tags are missing');
            }

            $tag_id = $tag_ar['id'];
            if (!is_numeric($tag_id) and !$tag = $tagRepo->findOneBy(['title' => $tag_id, 'parent' => $parent])) {
                //create a new tag
                $tag = new Tags();
                $tag->setTitle($tag_id);
                $tag->setParent($parent);
                $this->em->persist($tag);
                $this->em->flush();
            }elseif(is_numeric($tag_id)) {
                $tag = $tagRepo->findOneBy(['id' => $tag_id, 'parent' => $parent]);
            }else{
                throw new \Exception('Some error occured');
            }

            //check if not present already
            $tagRepo = $this->em->getRepository('NFQAssistanceBundle:Tag2User');
            $tag2userCheck = $tagRepo->findOneBy(['tag' => $tag, 'user' => $user]);
            if (!empty($tag2userCheck)) {
                throw new \Exception('Tag already assigned to user');
            }

            $tag2user = new Tag2User();
            $tag2user->setTag($tag);
            $tag2user->setUser($user);

            $this->em->persist($tag2user);
            $this->em->flush();

            $response['status'] = 'success';
        } catch (\Exception $ex) {
            $response['status'] = 'failed';
            $response['message'] = $ex->getMessage();
        }
        return $response;

    }

    /**
     * @return array $response
     */
    public function findTag()
    {
        $response = [];
        try {
            $tag = $this->getRequest()->get('tag', '');
            $parent_id = $this->getRequest()->get('parent_id', '');

            $tagRepo =  $this->em->getRepository('NFQAssistanceBundle:Tags');

            $parent = $tagRepo->findOneById($parent_id);

            $tags = $tagRepo->matchEnteredTags($tag, $parent);

            $response ['status'] = 'success';
            $response['tags'] = $tags;
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * removes tags assigned to user
     * @return status array
     */
    public function removeTag()
    {
        $response = [];
        try {

            if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new AccessDeniedException();
            }

            $tagAr = $this->getRequest()->get('tag', null);
            if (!isset($tagAr['id'])) {
                throw new \Exception('no tag sent');
            } else {
                $tag_id = $tagAr['id'];
            }
            $tagRepo = $this->em->getRepository('NFQAssistanceBundle:Tags');
            if (!is_numeric($tag_id)) {
                $tag = $tagRepo->findOneByTitle($tag_id);
            } else {
                $tag = $tagRepo->findOneById($tag_id);
            }
            if (!$tag) {
                throw new \Exception('this tag was not found');
            }

            $user = $this->getUser();

            //check if is parent and delete all children
            if(is_null($tag->getParent())){
                //delete all child tags
                $childTags = $tagRepo->getTagChildsByParent($tag, $user);
                foreach($childTags as $t2u){
                    $this->em->remove($t2u);
                }
            }

            $tag2userRepo = $this->em->getRepository('NFQAssistanceBundle:Tag2User');
            $tag2user = $tag2userRepo->findOneBy(['user' => $user, 'tag' => $tag]);

            if (!$tag2user) {
                throw new \Exception('user does not have this tag');
            }

            $this->em->remove($tag2user);
            $this->em->flush();

            $response['status'] = 'success';
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}