<?php

namespace ToDoApp\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ToDoApp\Domain\Exception\NotFoundException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;
use ToDoApp\Domain\Task\TaskRepository as Base;
use ToDoApp\Domain\Task\TaskStatus;
use ToDoApp\Entity\Task as DbTask;

class TaskRepository extends ServiceEntityRepository implements Base
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DbTask::class);
    }

    public function getNextId($envPostfix = ''): TaskId
    {
        $sql = sprintf(
            'SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "todoapp%s" AND TABLE_NAME = "task"',
            $envPostfix
        );

        $stm = $this->_em->getConnection()->prepare($sql);

        $stm->executeQuery();
        $result = $stm->fetchColumn();

        return TaskId::create((int) $result);
    }

    public function save(Task $task)
    {
        $result = parent::find($task->getTaskId()->value());
        $dbTask = self::convertToEntity($task, $result);

        if (!$result) {
            $this->_em->persist($dbTask);
        }

        $this->_em->flush($dbTask);
    }

    public function delete(Task $task)
    {
        $dbTask = parent::find($task->getTaskId()->value());
        $this->_em->remove($dbTask);
        $this->_em->flush($dbTask);
    }

    public function findById(TaskId $id): Task
    {
        $dbTask = parent::find($id->value());

        if (!$dbTask) {
            throw new NotFoundException('Task', (string) $id);
        }

        return self::convertToAggregate($dbTask);
    }

    public function findOneBy(array $criteria, array $orderBy = null): Task
    {
        $dbTask = parent::findOneBy($criteria, $orderBy);

        if (!$dbTask) {
            throw new NotFoundException('Task', array_keys($criteria)[0], array_values($criteria)[0]);
        }

        return self::convertToAggregate($dbTask);
    }

    public function findAll(): array
    {
        $list = parent::findAll();
        $tasks = [];
        foreach ($list as $dbTask) {
            $tasks[] = self::convertToAggregate($dbTask);
        }

        return $tasks;
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $list = parent::findBy($criteria, $orderBy, $limit, $offset);
        $tasks = [];
        foreach ($list as $dbTask) {
            $tasks[] = self::convertToAggregate($dbTask);
        }

        return $tasks;
    }

    public function uncompletedTaskNameExists(TaskName $taskName): bool
    {
        try {
            $this->findOneBy(['name' => (string) $taskName, 'isCompleted' => false]);
        } catch (NotFoundException $exception) {
            return false;
        }

        return true;
    }

    private static function convertToAggregate(DbTask $dbTask): Task
    {
        return new Task(
            TaskId::create($dbTask->getId()),
            TaskName::create($dbTask->getName()),
            $dbTask->isCompleted() ? TaskStatus::completed() : TaskStatus::notCompleted(),
            $dbTask->getCreatedAt(),
            $dbTask->getLastUpdatedAt()
        );
    }

    private static function convertToEntity(Task $task, DbTask $dbTask = null): DbTask
    {
        $dbTask = $dbTask ?? new DbTask();

        $dbTask->setId($task->getTaskId()->value())
            ->setName($task->getTaskName()->value())
            ->setIsCompleted($task->getTaskStatus()->isCompleted())
            ->setCreatedAt($task->getCreatedAt())
            ->setLastUpdatedAt($task->getLastUpdatedAt());

        return $dbTask;
    }
}
