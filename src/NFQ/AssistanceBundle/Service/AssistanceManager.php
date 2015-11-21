<?php

namespace NFQ\AssistanceBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

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
     * AssistanceManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array|string
     */
    public function getCategoryTree()
    {
        $repo = $this->em->getRepository('NFQAssistanceBundle:AssistanceCategories');

        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>'
        );

        $htmlTree = $repo->childrenHierarchy(
            null, /* starting from root nodes */
            false, /* true: load all children, false: only direct */
            $options
        );

        return $htmlTree;
    }
}