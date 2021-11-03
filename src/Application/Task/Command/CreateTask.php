<?php

namespace ToDoApp\Application\Task\Command;

use ToDoApp\Application\Command;
use ToDoApp\Domain\Task\TaskName;

/**
 * @codeCoverageIgnore
 */
class CreateTask implements Command
{
    private TaskName $name;

    public function __construct(string $name)
    {
        $this->name = TaskName::create($name);
    }

    public function getName(): TaskName
    {
        return $this->name;
    }
}
