<?php

namespace ToDoApp\Application\Task\CommandHandler;

use ToDoApp\Application\CommandHandler;
use ToDoApp\Application\EventAwareHandler;
use ToDoApp\Application\Task\Command\CompleteTask;
use ToDoApp\Application\Task\Command\DeleteTask;
use ToDoApp\Domain\Task\TaskRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DeleteTaskHandler implements CommandHandler
{
    use EventAwareHandler;

    private TaskRepository $repository;

    public function __construct(TaskRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(DeleteTask $command)
    {
        $task = $this->repository->findById($command->getId());
        $task->delete();

        $this->repository->delete($task);
        $this->recordFor($task);
    }
}
