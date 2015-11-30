<?php

namespace NFQ\AssistanceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NFQ\UserBundle\Entity\User;

/**
 * Class AssistanceRequestRepository
 * @package src\NFQ\AssistanceBundle\Repository
 */
class AssistanceRequestRepository extends EntityRepository
{

    public function getMyRequests(User $user, $limit=5)
    {

        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ar')
            ->from('NFQAssistanceBundle:AssistanceRequest', 'ar')
            ->where('ar.owner = :user')
            ->setParameter('user', $user);
        return $qb->getQuery()->getResult();
    }

    public function getRequestsForMe(User $user, $myTags, $limit=5)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ar')
            ->from('NFQAssistanceBundle:AssistanceRequest', 'ar')
            ->leftJoin('ar.tags', 't')
            ->where('ar.owner != :user')
            ->andWhere('t.id IN (:myTags)')
            ->setParameter('myTags', $myTags)
            ->setParameter(':user', $user);
        return $qb->getQuery()->setMaxResults($limit)->getResult();
    }
}