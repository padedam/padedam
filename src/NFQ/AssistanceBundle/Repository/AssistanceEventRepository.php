<?php

namespace NFQ\AssistanceBundle\Repository;

use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class AssistanceEventRepository
 * @package NFQ\AssistanceBundle\Repository
 */
class AssistanceEventRepository extends \Doctrine\ORM\EntityRepository
{

    public function findLatest()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $qb->select('ae, u, ar')
            ->from('NFQAssistanceBundle:AssistanceEvent', 'ae')
            ->leftJoin('ae.assistanceRequest', 'ar')
            ->leftJoin('ar.owner', 'u')
            ->where('ae.eventTime > :eventTime')
            ->setParameter('eventTime', new \DateTime('-15 minutes'))
            ->groupBy('ae.assistanceRequest')
            ->orderBy('ae.eventTime', 'DESC');
        return $qb->getQuery()->getResult();

    }
}
