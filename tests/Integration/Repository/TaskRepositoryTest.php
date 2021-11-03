<?php

namespace ToDoApp\Tests\Integration\Repository;

use ToDoApp\Domain\Exception\NotFoundException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;
use ToDoApp\Domain\Task\TaskStatus;
use ToDoApp\Entity\Task as DbTask;
use ToDoApp\Repository\TaskRepository;
use ToDoApp\Tests\Integration\IntegrationTestCase;
use function PHPUnit\Framework\assertFalse;

class TaskRepositoryTest extends IntegrationTestCase
{
    public function testGetNextId()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);

        $taskId = $repository->getNextId('_test');

        self::assertInstanceOf(TaskId::class, $taskId);
        self::assertGreaterThanOrEqual(1, $taskId->value());
    }

    public function testSave()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);
        $taskId = $repository->getNextId('_test');
        $taskName = TaskName::create('a task name');
        $task = new Task(
            $taskId,
            $taskName,
            TaskStatus::notCompleted(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $repository->save($task);

        $result = $repository->findById($taskId);
        self::assertEquals($taskName, $result->getTaskName());
        self::assertEquals($taskId, $result->getTaskId());
        self::assertFalse($result->getTaskStatus()->isCompleted());
    }

    public function testDelete()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);
        $taskId = $repository->getNextId('_test');
        $task = new Task(
            $taskId,
            TaskName::create('a task name'),
            TaskStatus::notCompleted(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
        $repository->save($task);

        $repository->delete($task);

        $this->expectException(NotFoundException::class);
        $repository->findById($taskId);
    }

    public function testUncompletedTaskNameDoesNotExist()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);

        self:: assertFalse($repository->uncompletedTaskNameExists(TaskName::create('new task name')));
    }

    public function testUncompletedTaskNameExists()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);
        $taskName = TaskName::create('existing uncompleted task');
        $task = new Task(
            $repository->getNextId('_test'),
            $taskName,
            TaskStatus::notCompleted(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $repository->save($task);

        self::assertTrue($repository->uncompletedTaskNameExists($taskName));
    }

    public function testCompletedTaskNameExists()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);
        $taskName = TaskName::create('existing completed task');
        $task = new Task(
            $repository->getNextId('_test'),
            $taskName,
            TaskStatus::completed(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $repository->save($task);

        self:: assertFalse($repository->uncompletedTaskNameExists($taskName));
    }

    public function testSaveExistingEntity()
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(DbTask::class);
        $taskId = $repository->getNextId('_test');
        $taskName = TaskName::create('another name');
        $task = new Task(
            $taskId,
            TaskName::create('a task name'),
            TaskStatus::notCompleted(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
        $repository->save($task);
        $task->updateName($taskName);

        $repository->save($task);

        $result = $repository->findById($taskId);
        self::assertEquals($taskName, $result->getTaskName());
        self::assertEquals($taskId, $result->getTaskId());
        self::assertFalse($result->getTaskStatus()->isCompleted());
    }
}
