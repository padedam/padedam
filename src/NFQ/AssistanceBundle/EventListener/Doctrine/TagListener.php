<?php

namespace NFQ\AssistanceBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use NFQ\AssistanceBundle\Document\TagDocument;
use NFQ\AssistanceBundle\Entity\Tags;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;


class TagListener
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * TagListener constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Tags $entity
     * @param LifecycleEventArgs $event
     */
    public function prePersist(Tags $entity, LifecycleEventArgs $event)
    {
        $tagDocument = new TagDocument();
        $tagDocument->setId('tag-'.$entity->getId());
        $tagDocument->setName($entity->getTitle());

        $this->repository->getManager()->persist($tagDocument);
        $this->repository->getManager()->commit();
    }

    /**
     * @param Tags $entity
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Tags $entity, LifecycleEventArgs $event)
    {

    }

    /**
     * @param Tags $entity
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(Tags $entity, PreUpdateEventArgs $event)
    {

    }

    /**
     * @param Tags $entity
     * @param LifecycleEventArgs $event
     */
    public function preRemove(Tags $entity, LifecycleEventArgs $event)
    {
        $this->entityId = $entity->getId();
    }

    /**
     * @param Tags $entity
     * @param LifecycleEventArgs $event
     */
    public function postRemove(Tags $entity, LifecycleEventArgs $event)
    {
        $this->uploadManager->removeFiles($this->entityId);
    }
}