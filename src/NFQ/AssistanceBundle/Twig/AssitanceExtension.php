<?php

namespace NFQ\AssistanceBundle\Twig;

use NFQ\AssistanceBundle\Service\AssistanceManager;

/**
 * Class AssitanceExtension
 * @package NFQ\AssistanceBundle\Twig
 */
class AssitanceExtension extends \Twig_Extension
{
    /**
     * @var AssistanceManager
     */
    private $manager;

    public function __construct(AssistanceManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'get_assistance_tags',
                [$this, 'getAssistanceTags'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'get_my_requests',
                [$this, 'getMyRequests']
            ),
            new \Twig_SimpleFunction(
                'get_requests_for_me',
                [$this, 'getRequestsForMe']
            ),
            new \Twig_SimpleFunction(
                'get_review_list',
                [$this, 'getReviewList']
            )
        ];
    }

    /**
     * @return array|string
     */
    public function getAssistanceTags()
    {
        return $this->manager->getTagTree();
    }


    /**
     * @return string
     */
    public function getMyRequests()
    {
        return $this->manager->getMyRequests();
    }


    /**
     * @return string
     */
    public function getRequestsForMe()
    {
        return $this->manager->getRequestsForMe();
    }

    /**
     * @return array
     */
    public function getReviewList()
    {
        return $this->manager->getReviewList();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'assistance_extension';
    }
}