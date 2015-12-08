<?php

namespace NFQ\UserBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use NFQ\UserBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use NFQ\AssistanceBundle\Entity\Tags;
use NFQ\AssistanceBundle\Entity\Tag2User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * TagManager constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $requestStack
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorage $tokenStorage
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $em,
        RequestStack $requestStack,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorage $tokenStorage,
        ValidatorInterface $validator)
    {
        $this->em = $em;

        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
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
        foreach ($myRootTags as $k => $tag) {
            $mytagsId[] = $tag['id'];
        }
        foreach ($allRootTags as $k => $tag) {
            if (in_array($tag['id'], $mytagsId)) {
                unset($allRootTags[$k]);
            }
        }

        return array('my' => $myRootTags, 'root' => $allRootTags);

    }

    /**
     * @return array $response
     */
    public function getMyChildTags()
    {

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
        } catch (\Exception $e) {
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;

    }

    /**
     * @return array
     */
    public function saveTag()
    {
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
            } else {
                $parent = null;
            }

            if (!is_array($tag_ar)) {
                throw new \InvalidArgumentException('Tags are missing');
            }

            if (!is_numeric($tag_ar['id'])){
                $tag_id =  $this->suggestSpelling($tag_ar['id']);
            }else{
                $tag_id = $tag_ar['id'];
            }
            if (!is_numeric($tag_id) and !$tag = $tagRepo->findOneBy(['title' => $tag_id, 'parent' => $parent])) {
                //create a new tag
                $tag = new Tags();
                $tag->setTitle($tag_id);
                $tag->setParent($parent);
                $this->em->persist($tag);
                $this->em->flush();
            } elseif (is_numeric($tag_id)) {
                $tag = $tagRepo->findOneBy(['id' => $tag_id, 'parent' => $parent]);
            } else {
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
            $param = $this->getRequest()->get('tag', '');
            $parent_id = $this->getRequest()->get('parent_id', '');
            if( mb_strlen($param) < 4 or !$parent_id ){
                return;
            }
            $tag = $this->suggestSpelling($param);

            $tagRepo = $this->em->getRepository('NFQAssistanceBundle:Tags');
            $parent = $tagRepo->findOneById($parent_id);

            $fetched = $tagRepo->matchEnteredTags([$tag, $param], $parent);

            $tags = [];
            if(!$this->check_in_array($fetched, $param)) {
                $tags[] = ['id' => $param, 'text' => $param . ' (Sukurti)'];
            }
            if($tag and $tag != $param and !$this->check_in_array($fetched, $tag)) {
                $tags[] = ['id' => $tag, 'text' => $tag.' (Sukurti)'];
            }

            $response ['status'] = 'success';
            $response['tags'] = array_merge($tags, $fetched);
        } catch (\Exception $e) {
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }


    /**
     * @param $arr
     * @param $value
     * @param string $type
     * @return bool
     */
    private function check_in_array($arr, $value, $type = "text") {
        return count(array_filter($arr, function($var) use ($type, $value) {
            return $var[$type] === $value;
        })) !== 0;
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
            if (is_null($tag->getParent())) {
                //delete all child tags
                $childTags = $tagRepo->getTagChildsByParent($tag, $user);
                foreach ($childTags as $t2u) {
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
        } catch (\Exception $e) {
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return array
     */
    public function suggestTag()
    {
        $response = [];
        try {
            $tags = $this->suggestWordstart($this->getRequest()->get('tag', null));
            if ( empty($tags) ) {
                throw new \Exception('no tags');
            }
            $tagsRepo = $this->em->getRepository("NFQAssistanceBundle:Tags");
            $foundTag = $tagsRepo->suggestTag($tags);
            $result = [];
            foreach($foundTag as $tag){
                $process = $this->processTag($tag);
                $result[$process['id']] = $process['id'];
            }
            $response['status'] = 'success';
            $response['tags'] = array_values($result);
        } catch (\Exception $e) {
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    private function processTag($tag){
        $result= [];
        if ( isset( $tag['parent'] ) and !empty( $tag['parent'] ) ) {
            $result['id'] = $tag['parent']['id'];
            $result['text'] = $tag['parent']['title'];
        } elseif ( isset( $tag['id'] ) and is_numeric( $tag['id'] ) ) {
            $result['id'] = $tag['id'];
            $result['text'] = $tag['title'];
        }
        return $result;
    }


    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param string $word
     * @return string|void
     */
    private function suggestSpelling($word=''){
        if( mb_strlen($word) < 4 or $this->removeWords($word)){
            return;
        }
        //check if not more than 1 word
        $words = explode(' ', $word);
        $result = '';
        $pspell_link = pspell_new("lt", null, null, "utf-8");
        foreach($words as $item){
            if (!pspell_check($pspell_link, $item)) {
                $suggestions = pspell_suggest($pspell_link, $item);
                $item = strtolower(reset($suggestions));
            }
            $result .= $item.' ';
        }

        return rtrim($result);
    }

    /**
     * @param string $word
     * @return string|void
     */
    private function suggestWordstart($word){

        //check if not more than 1 word
        $words = explode(' ', $word);
        $results = [];
        foreach($words as $w){
            $w = $this->remAppendix(trim($w));
            if( mb_strlen($w) < 4 or $this->removeWords($w)){
                continue;
            }
            $suggestions = $this->pspell_suggest($w);
            $first_suggested = mb_strtolower(reset($suggestions));
            $result = mb_substr($first_suggested, 0, mb_strlen($w)/2);
            if( mb_strlen($result) > 3 and !in_array($result, $results) ) {
                $results[] = $result;
            }elseif(!in_array($first_suggested, $results)){
                $results[] = $first_suggested;
            }
            $myentry = mb_substr($w, 0, mb_strlen($w)/2);
            if(!in_array($myentry, $results) and strlen($myentry) > 3){
                $results[] = $myentry;
            }elseif(!in_array($myentry, $results) and strlen($myentry) < 4){
                $results[] = $w;
            }
        }
        return $results;
    }

    /**
     * @param $word
     * @return string
     */
    private function remAppendix($word)
    {
        $appendix = ['pa', 'pra', 'pri', 'nu', 'iš', 'is', 'su', 'pri', 'ne'];
        foreach ($appendix as $what) {
            if (($pos = mb_strpos($word, $what)) === 0) return mb_substr($word, 2);
        }
        return $word;
    }

    private function removeWords($w){
        $words = ['vis', 'man', 'aš', 'juo', 'jum', 'kaip', 'mok', 'ir'];
        foreach($words as $word){
            if(strpos($w, $word) === 0){
                return true;
            }
        }
        return false;
    }

    /**
     * @param $tag
     * @return array
     */
    private function pspell_suggest($tag)
    {
        $pspell_link = pspell_new("lt", null, null, "utf-8");
        return pspell_suggest($pspell_link, $tag);
    }

}