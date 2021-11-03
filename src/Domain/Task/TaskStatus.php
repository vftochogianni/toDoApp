<?php

namespace ToDoApp\Domain\Task;

use ToDoApp\Domain\ValueObject;

/**
 * @codeCoverageIgnore
 */
class TaskStatus extends ValueObject
{
    private bool $isCompleted;

    private function __construct(bool $isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }

    public static function completed(): self
    {
        return new self(true);
    }

    public static function notCompleted(): self
    {
        return new self(false);
    }

    public function value(): bool
    {
        return $this->isCompleted;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }
}
