<?php


namespace NFQ\ReviewsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NFQ\UserBundle\Entity\User;

/**
 * Class ThanksRepository
 * @package NFQ\ReviewsBundle\Repository
 */
class ThanksRepository extends EntityRepository
{

    /**
     * @return array
     */
    public function getTopHelpers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.number as number, u.first_name as helper')
            ->from('NFQReviewsBundle:Thanks', 't')
            ->leftJoin('t.helper', 'u')
            ->orderBy('t.number', 'DESC');

        return $qb->getQuery()->setMaxResults(5)->getResult();
    }

    public function findOneByUser(User $user)
    {
        $result = $this->createQueryBuilder('t')
            ->leftJoin('t.helper', 'h')
            ->where('h.id=' . $user->getId())
            ->getQuery()
            ->getResult();

        if (count($result) < 1) {
            return null;
        }
        return $result[0];
    }

}