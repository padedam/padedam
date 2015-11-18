<?php


namespace NFQ\AssistanceBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use NFQ\AssistanceBundle\Entity\Tags;
use NFQ\UserBundle\Entity\User;

/**
 * Class TagsRepository
 * @package NFQ\AssistanceBundle\Repository
 */
class TagsRepository extends NestedTreeRepository
{
    /**
     * @param User $user
     * @param $parent
     * @return array
     */
    public function getMyTags(\NFQ\UserBundle\Entity\User $user, $parent)
    {

        $qb = $this->getEntityManager()
        ->createQueryBuilder();
        $qb->select('t.id AS id, t.title as text')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->leftJoin('t.usersWithTag', 't2u')
            ->where('t2u.user = :user')
            ->setParameter('user', $user)
            ->andWhere('t.parent = :parent')
            ->setParameter('parent', $parent);
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getMyRootTags(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t, r')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->innerJoin('t.usersWithTag', 't2u')
            ->leftJoin('t.parent', 'r')
            ->where('t2u.user = :user')
            ->andWhere('t.parent IS NULL')
        ->setParameter(':user', $user );

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @return array
     */
    public function getAllRootTags()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t, r')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->leftJoin('t.parent', 'r')
            ->where('t.parent IS NULL');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $tag
     * @param $parent
     * @return array
     */
    public function matchEnteredTags($tag, $parent)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.id AS id, t.title as text')
            ->from('NFQAssistanceBundle:Tags', 't')
            ->where('t.title LIKE :title')
            ->andWhere('t.parent = :parent')
            ->setParameter(':title', $tag . '%')
            ->setParameter(':parent', $parent);

        return $qb->getQuery()->getArrayResult();
    }

}