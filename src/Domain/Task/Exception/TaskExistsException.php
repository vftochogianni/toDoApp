<?php

namespace ToDoApp\Domain\Task\Exception;

use ToDoApp\Domain\Task\TaskName;

class TaskExistsException extends \Exception
{
    public function __construct(TaskName $taskName)
    {
        parent::__construct(sprintf('Task with name "%s" already exists.', $taskName->value()));
    }
}
