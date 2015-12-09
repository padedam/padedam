<?php

namespace NFQ\UserBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLastUsers($limit = 5)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('u')
            ->from('NFQUserBundle:User', 'u')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }
}