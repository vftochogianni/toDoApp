<?php

namespace ToDoApp\Application\Task\CommandHandler;

use ToDoApp\Application\CommandHandler;
use ToDoApp\Application\EventAwareHandler;
use ToDoApp\Application\Task\Command\UpdateTaskName;
use ToDoApp\Domain\Task\Exception\TaskExistsException;
use ToDoApp\Domain\Task\TaskRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UpdateTaskNameHandler implements CommandHandler
{
    use EventAwareHandler;

    private TaskRepository $repository;

    public function __construct(TaskRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(UpdateTaskName $command)
    {
        if ($this->repository->uncompletedTaskNameExists($command->getName())) {
            throw new TaskExistsException($command->getName());
        }

        $task = $this->repository->findById($command->getId());
        $task->updateName($command->getName());

        $this->repository->save($task);
        $this->recordFor($task);
    }
}
