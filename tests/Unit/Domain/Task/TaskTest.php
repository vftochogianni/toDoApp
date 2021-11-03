<?php

namespace ToDo\Tests\Unit\Domain\Task;

use PHPUnit\Framework\TestCase;
use ToDoApp\Domain\Task\Event\TaskCompleted;
use ToDoApp\Domain\Task\Event\TaskCreated;
use ToDoApp\Domain\Task\Event\TaskDeleted;
use ToDoApp\Domain\Task\Event\TaskNameUpdated;
use ToDoApp\Domain\Task\Exception\TaskCannotBeDeletedException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;

class TaskTest extends TestCase
{
    private Task $task;

    public function setUp(): void
    {
        $this->task = Task::create(
            TaskId::create(1),
            TaskName::create('some task')
        );
        $this->task->resetEvents();
    }

    public function testCreate()
    {
        $task = Task::create(
            TaskId::create(2),
            TaskName::create('some task')
        );

        $recordedEvents = $task->getEvents();
        self::assertCount(1, $recordedEvents);
        self::assertInstanceOf(TaskCreated::class, $recordedEvents[0]);
    }

    public function testUpdateName()
    {
        $this->task->updateName(TaskName::create('new task name'));

        $recordedEvents = $this->task->getEvents();
        self::assertCount(1, $recordedEvents);
        self::assertInstanceOf(TaskNameUpdated::class, $recordedEvents[0]);
    }

    public function testComplete()
    {
        $this->task->complete();

        $recordedEvents = $this->task->getEvents();
        self::assertCount(1, $recordedEvents);
        self::assertInstanceOf(TaskCompleted::class, $recordedEvents[0]);
    }

    public function testDelete()
    {
        $this->task->complete();
        $this->task->resetEvents();

        $this->task->delete();

        $recordedEvents = $this->task->getEvents();
        self::assertCount(1, $recordedEvents);
        self::assertInstanceOf(TaskDeleted::class, $recordedEvents[0]);
    }

    public function testDeleteShouldThrowExceptionWhenTaskIsNotCompletedFirst()
    {
        $this->expectException(TaskCannotBeDeletedException::class);

        $this->task->delete();
    }
}
