<?php

namespace NFQ\AssistanceBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use NFQ\AssistanceBundle\Document\TagDocument;
use NFQ\AssistanceBundle\Entity\Tags;
use ONGR\ElasticsearchBundle\ORM\Repository;

class TagListener
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var int
     */
    private $entityId;

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
    public function postPersist(Tags $entity, LifecycleEventArgs $event)
    {
        $this->persistToElastic($entity);
    }

    /**
     * @param Tags $entity
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(Tags $entity, LifecycleEventArgs $event)
    {
        $this->persistToElastic($entity);
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
        $tagDocumentId = 'tag-' . $this->entityId;

        $this->repository->remove($tagDocumentId);
    }

    /**
     * @param Tags $entity
     */
    private function persistToElastic(Tags $entity)
    {
        $tagDocument = new TagDocument();
        $tagDocument->setId('tag-' . $entity->getId());
        $tagDocument->setName($entity->getTitle());

        $this->repository->getManager()->persist($tagDocument);
        $this->repository->getManager()->commit();
    }
}