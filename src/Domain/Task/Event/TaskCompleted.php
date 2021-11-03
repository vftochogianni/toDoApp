<?php

namespace ToDoApp\Domain\Task\Event;

use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Task\Task;

class TaskCompleted extends DomainEvent
{
    public const NAME = 'task.completed';

    private array $payload;

    public function __construct(Task $task)
    {
        $this->payload = $task->toArray();
        $this->taskId = $task->getTaskId()->value();
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function recordedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
