<?php

namespace ToDoApp\Subscriber;

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Task\Event;
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
        return [
            Event\TaskCreated::class => ['onDomainEvent', -999],
            Event\TaskNameUpdated::class => ['onDomainEvent', -999],
            Event\TaskCompleted::class => ['onDomainEvent', -999],
            Event\TaskDeleted::class => ['onDomainEvent', -999],
        ];
    }

    public function onDomainEvent(DomainEvent $event)
    {
        $this->repository->save(SystemEvent::fromDomainEvent($event));
        $event->stopPropagation();
    }
}
