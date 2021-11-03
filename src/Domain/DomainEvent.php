<?php

namespace ToDoApp\Domain;

use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class DomainEvent extends Event implements StoppableEventInterface
{
    protected int $taskId;

    abstract public function getPayload(): array;

    abstract public function getName(): string;

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    abstract public function recordedAt(): \DateTimeImmutable;
}
