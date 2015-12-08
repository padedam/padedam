<?php

namespace NFQ\AssistanceBundle\EventListener\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use NFQ\AssistanceBundle\Entity\AssistanceEvent;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;

class AsistanceRequestListener
{
    public function postPersist(AssistanceRequest $entity, LifecycleEventArgs $args)
    {
        $this->persistEvent($entity, $args->getEntityManager());
    }

    /**
     * @param AssistanceRequest $entity
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(AssistanceRequest $entity, LifecycleEventArgs $args)
    {

        $this->persistEvent($entity, $args->getEntityManager());
    }

    /**
     * @param AssistanceRequest $entity
     * @param EntityManagerInterface $em
     */
    private function persistEvent(AssistanceRequest $entity, EntityManagerInterface $em)
    {

        $event = new AssistanceEvent();
        $event->setAssistanceRequest($entity);
        $event->setAssistanceRequestStatus($entity->getStatus());
        $event->setUser($entity->getHelper());

        $em->persist($event);
        $em->flush();

    }

}