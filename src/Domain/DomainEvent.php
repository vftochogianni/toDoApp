<?php

namespace ToDoApp\Domain;

use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class DomainEvent extends Event implements StoppableEventInterface
{
    protected string $userId;

    abstract public function getPayload(): array;

    abstract public function getName(): string;

    public function getUserId(): string
    {
        return $this->userId;
    }

    abstract public function recordedAt(): \DateTimeImmutable;
}
