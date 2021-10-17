<?php

namespace ToDoApp\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use ToDoApp\Application\Command;
use ToDoApp\Subscriber\SystemEventSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

abstract class BaseController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;
    protected LoggerInterface $logger;

    public function __construct(EventDispatcherInterface $eventDispatcher, LoggerInterface $logger, ManagerRegistry $registry)
    {
        $this->logger = $logger;

        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcher->addSubscriber(new SystemEventSubscriber($logger, $registry));
    }

    /** @return mixed */
    public function dispatchCommand(Command $command)
    {
        $envelope = $this->dispatchMessage($command);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
