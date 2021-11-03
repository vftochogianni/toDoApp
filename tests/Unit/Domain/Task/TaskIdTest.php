<?php

namespace ToDo\Tests\Unit\Domain\Task;

use PHPUnit\Framework\TestCase;
use ToDoApp\Domain\Exception\NotValidException;
use ToDoApp\Domain\Task\TaskId;

class TaskIdTest extends TestCase
{
    public function testCreate()
    {
        $taskId = TaskId::create(1);

        self::assertEquals(1, $taskId->value());
        self::assertEquals('1', (string) $taskId);
    }

    public function testCreateShouldThrowExceptionWhenIdIsZero()
    {
        $this->expectException(NotValidException::class);

        TaskId::create(0);
    }

    public function testCreateShouldThrowExceptionWhenIdIsNegativeNumber()
    {
        $this->expectException(NotValidException::class);

        TaskId::create(-1);
    }
}
