<?php

namespace ToDoApp\Application\Task\Command;

use ToDoApp\Application\Command;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;

/**
 * @codeCoverageIgnore
 */
class UpdateTaskName implements Command
{
    private TaskName $name;
    private TaskId $id;

    public function __construct(int $id, string $name)
    {
        $this->name = TaskName::create($name);
        $this->id = TaskId::create($id);
    }

    public function getName(): TaskName
    {
        return $this->name;
    }

    public function getId(): TaskId
    {
        return $this->id;
    }
}
