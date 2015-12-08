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
                'get_my_requests',
                [$this, 'getMyRequests']
            ),
            new \Twig_SimpleFunction(
                'get_requests_for_me',
                [$this, 'getRequestsForMe']
            ),
            new \Twig_SimpleFunction(
                'get_my_taken_requests',
                [$this, 'getMyTakenRequests']
            ),
            new \Twig_SimpleFunction(
                'get_review_list',
                [$this, 'getReviewList']
            ),
            new \Twig_SimpleFunction(
                'front_page_reviews',
                [$this, 'getReviewsFrontPage']
            ),
            new \Twig_SimpleFunction(
                'front_page_requests',
                [$this, 'getRequestsFrontPage']
            )
        ];
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
     * @return array
     */
    public function getMyTakenRequests()
    {
        return $this->manager->getMyTakenRequests();
    }

    public function getRequestsFrontPage()
    {
        return $this->manager->getRequestsFrontPage();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'assistance_extension';
    }
}