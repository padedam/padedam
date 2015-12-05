<?php

namespace NFQ\ReviewsBundle\Entity;


use Doctrine\ORM\EntityRepository;
use NFQ\UserBundle\Entity\User;

class ThanksRepository extends EntityRepository
{
    public function findOneByUser(User $user){
        $result = $this->createQueryBuilder('t')
            ->leftJoin('t.helper','h')
            ->where('h.id='.$user->getId())
            ->getQuery()
            ->getResult();

        if(count($result)<1){
            return null;
        }
        return $result[0];
    }
}