<?php

namespace ToDoApp\Application\Task;

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
        return $this->repository->findAll();
    }
}
