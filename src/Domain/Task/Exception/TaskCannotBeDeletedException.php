<?php

namespace ToDoApp\Domain\Task\Exception;

use ToDoApp\Domain\Task\TaskId;

class TaskCannotBeDeletedException extends \Exception
{
    public function __construct(TaskId $taskId)
    {
        parent::__construct(sprintf('Task with id "%d" cannot be deleted.', $taskId->value()));
    }
}
