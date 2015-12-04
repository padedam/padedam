<?php

namespace NFQ\AssistanceBundle\Twig;

use NFQ\AssistanceBundle\Service\ReviewManager;


/**
 * Class ReviewExtension
 * @package NFQ\AssistanceBundle\Twig
 */
class ReviewExtension extends \Twig_Extension
{


    private $manager;

    public function __construct(ReviewManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'get_review_list',
                [$this, 'getReviewList']
            ),
            new \Twig_SimpleFunction(
                'front_page_heroes',
                [$this, 'getHeroesList']
            )
        ];
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
    public function getHeroesList()
    {
        return $this->manager->getHeroesList();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'review_extension';
    }
}