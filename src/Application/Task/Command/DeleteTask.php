<?php

namespace ToDoApp\Application\Task\Command;

use ToDoApp\Application\Command;
use ToDoApp\Domain\Task\TaskId;

/**
 * @codeCoverageIgnore
 */
class DeleteTask implements Command
{
    private TaskId $id;

    public function __construct(int $id)
    {
        $this->id = TaskId::create($id);
    }

    public function getId(): TaskId
    {
        return $this->id;
    }
}
