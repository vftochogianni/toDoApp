<?php

namespace ToDoApp\Domain\Task;

use ToDoApp\Domain\Aggregate;
use ToDoApp\Domain\Task\Event\TaskCompleted;
use ToDoApp\Domain\Task\Event\TaskCreated;
use ToDoApp\Domain\Task\Event\TaskDeleted;
use ToDoApp\Domain\Task\Event\TaskNameUpdated;
use ToDoApp\Domain\Task\Exception\TaskCannotBeDeletedException;

class Task extends Aggregate
{
    private TaskId $taskId;
    private TaskName $taskName;
    private TaskStatus $taskStatus;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $lastUpdatedAt;

    public function __construct(
        TaskId $taskId,
        TaskName $taskName,
        TaskStatus $taskStatus,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $lastUpdatedAt
    ) {
        $this->taskId = $taskId;
        $this->taskName = $taskName;
        $this->taskStatus = $taskStatus;
        $this->createdAt = $createdAt;
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getTaskName(): TaskName
    {
        return $this->taskName;
    }

    public function getTaskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLastUpdatedAt(): \DateTimeImmutable
    {
        return $this->lastUpdatedAt;
    }

    public static function create(TaskId $taskId, TaskName $taskName): self
    {
        $task = new self($taskId, $taskName, TaskStatus::notCompleted(), new \DateTimeImmutable(), new \DateTimeImmutable());

        $task->record(new TaskCreated($task));

        return $task;
    }

    public function updateName(TaskName $taskName): void
    {
        $this->taskName = $taskName;
        $this->lastUpdatedAt = new \DateTimeImmutable();

        $this->record(new TaskNameUpdated($this));
    }

    public function complete(): void
    {
        $this->taskStatus = TaskStatus::completed();
        $this->lastUpdatedAt = new \DateTimeImmutable();

        $this->record(new TaskCompleted($this));
    }

    public function delete(): void
    {
        if (!$this->taskStatus->isCompleted()) {
            throw new TaskCannotBeDeletedException($this->taskId);
        }

        $this->record(new TaskDeleted($this));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->taskId->value(),
            'name' => $this->taskName->value(),
            'isCompleted' => $this->taskStatus->isCompleted(),
            'createdAt' => $this->createdAt->getTimestamp(),
            'lastUpdatedAt' => $this->lastUpdatedAt->getTimestamp(),
            'isDeleted' => false,
        ];
    }
}
