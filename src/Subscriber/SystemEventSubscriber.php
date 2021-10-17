<?php

namespace ToDoApp\Subscriber;

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Entity\SystemEvent;
use ToDoApp\Repository\SystemEventRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SystemEventSubscriber implements EventSubscriberInterface
{
    private SystemEventRepository $repository;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, ManagerRegistry $managerRegistry)
    {
        $this->repository = $managerRegistry->getRepository(SystemEvent::class);
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [];
    }

    public function onDomainEvent(DomainEvent $event)
    {
        $this->repository->save(SystemEvent::fromDomainEvent($event));
        $event->stopPropagation();
    }
}
