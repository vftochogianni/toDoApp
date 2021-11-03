<?php

namespace ToDoApp\Domain\Task;

interface TaskRepository
{
    public function getNextId($envPostfix = ''): TaskId;

    public function save(Task $task);

    public function delete(Task $task);

    public function findById(TaskId $id): Task;

    public function findAll(): array;

    public function uncompletedTaskNameExists(TaskName $taskName): bool;
}
