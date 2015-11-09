<?php


namespace NFQ\AssistanceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagsRepository extends EntityRepository
{

    public function getMyTags(\NFQ\UserBundle\Entity\User $user)
    {

        return $this->getEntityManager()
        ->createQuery(
            'SELECT   t.id AS id, t.title as text
            FROM     NFQAssistanceBundle:Tags t
            JOIN     t.usersWithTag t2u
            WHERE    t2u.user = :user
            '
        )->setParameter('user', $user)->getResult();
    }

    public function matchEnteredTags($tag)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.id AS id, t.title as text')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->where('t.title LIKE :title')
            ->setParameter(':title', $tag . '%');

        return $qb->getQuery()->getArrayResult();
    }

}