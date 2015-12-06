<?php

namespace NFQ\AssistanceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use NFQ\UserBundle\Entity\User;

/**
 * Class AssistanceRequestRepository
 * @package src\NFQ\AssistanceBundle\Repository
 */
class AssistanceRequestRepository extends EntityRepository
{

    /**
     * @param User $user
     * @param int $limit
     * @return array
     */
    public function getMyRequests(User $user, $limit=5)
    {

        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ar')
            ->from('NFQAssistanceBundle:AssistanceRequest', 'ar')
            ->where('ar.owner = :user')
            ->setParameter('user', $user)
            ->addOrderBy('ar.date', 'DESC');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @param $myTags
     * @param int $limit
     * @return array
     */
    public function getRequestsForMe(User $user, $myTags, $limit=5)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ar')
            ->from('NFQAssistanceBundle:AssistanceRequest', 'ar')
            ->leftJoin('ar.tags', 't')
            ->where('ar.owner != :user')
            ->andWhere('t.id IN (:myTags)')
            ->andWhere('ar.status = :status')
            ->setParameter('status', AssistanceRequest::STATUS_WAITING)
            ->setParameter('myTags', $myTags)
            ->setParameter('user', $user)
            ->addOrderBy('ar.date', 'DESC');
        return $qb->getQuery()->setMaxResults($limit)->getResult();
    }

    /**
     * @param User $user
     * @param int $limit
     * @return array
     */
    public function getMyTakenRequests(User $user, $limit=5)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ar')
            ->from('NFQAssistanceBundle:AssistanceRequest', 'ar')
            ->where('ar.helper = :user')
            ->andWhere('ar.status = :status')
            ->setParameter('status', AssistanceRequest::STATUS_TAKEN)
            ->setParameter('user', $user)
            ->addOrderBy('ar.date', 'DESC');
        return $qb->getQuery()->setMaxResults($limit)->getResult();
    }

    public function getRequestsFrontPage()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ar')
            ->from('NFQAssistanceBundle:AssistanceRequest', 'ar')
            ->where('ar.status = :status')
            ->setParameter('status', AssistanceRequest::STATUS_WAITING);
        return $qb->getQuery()->setMaxResults(5)->getResult();
    }
}