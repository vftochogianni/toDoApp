<?php

namespace ToDoApp\Application\Task\CommandHandler;

use ToDoApp\Application\CommandHandler;
use ToDoApp\Application\EventAwareHandler;
use ToDoApp\Application\Task\Command\CreateTask;
use ToDoApp\Domain\Task\Exception\TaskExistsException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateTaskHandler implements CommandHandler
{
    use EventAwareHandler;

    private TaskRepository $repository;

    public function __construct(TaskRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateTask $command)
    {
        if ($this->repository->uncompletedTaskNameExists($command->getName())) {
            throw new TaskExistsException($command->getName());
        }

        $task = Task::create($this->repository->getNextId(), $command->getName());

        $this->repository->save($task);
        $this->recordFor($task);

        return $task->getTaskId();
    }
}
