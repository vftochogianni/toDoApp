<?php

namespace ToDoApp\Application\Task;

use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskRepository;

/**
 * @codeCoverageIgnore
 */
class TaskService
{
    private TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTasks(): array
    {
        return array_map(function (Task $task): array {
            return $task->toArray();
        }, $this->repository->findAll());
    }
}
