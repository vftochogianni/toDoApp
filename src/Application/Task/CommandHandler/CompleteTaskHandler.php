<?php

namespace ToDoApp\Application\Task\CommandHandler;

use ToDoApp\Application\CommandHandler;
use ToDoApp\Application\EventAwareHandler;
use ToDoApp\Application\Task\Command\CompleteTask;
use ToDoApp\Domain\Task\TaskRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CompleteTaskHandler implements CommandHandler
{
    use EventAwareHandler;

    private TaskRepository $repository;

    public function __construct(TaskRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CompleteTask $command)
    {
        $task = $this->repository->findById($command->getId());
        $task->complete();

        $this->repository->save($task);
        $this->recordFor($task);
    }
}
