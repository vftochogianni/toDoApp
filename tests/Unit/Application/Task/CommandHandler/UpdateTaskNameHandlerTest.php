<?php

namespace ToDo\Tests\Unit\Application\Task\CommandHandler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ToDoApp\Application\Task\Command\UpdateTaskName;
use ToDoApp\Application\Task\CommandHandler\UpdateTaskNameHandler;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Exception\NotFoundException;
use ToDoApp\Domain\Task\Event\TaskNameUpdated;
use ToDoApp\Domain\Task\Exception\TaskExistsException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;
use ToDoApp\Domain\Task\TaskRepository;
use ToDoApp\Tests\Utils;

class UpdateTaskNameHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|TaskRepository */
    private $taskRepository;

    /** @var ObjectProphecy|EventDispatcherInterface */
    private $dispatcher;

    private UpdateTaskNameHandler $handler;

    protected function setUp(): void
    {
        $this->taskRepository = $this->prophesize(TaskRepository::class);
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->dispatcher->dispatch(Argument::type(DomainEvent::class))->willReturn(new class extends DomainEvent {
            public function getPayload(): array { return []; }
            public function getName(): string { return 'something.Happened'; }
            public function recordedAt(): \DateTimeImmutable { return new \DateTimeImmutable(); }
            public function getTaskId(): int { return 1; }
        });

        $this->handler = new UpdateTaskNameHandler($this->taskRepository->reveal(), $this->dispatcher->reveal());
    }

    public function testItUpdatesATask()
    {
        $task = Utils::createTask();
        $newTaskName = TaskName::create('updated task');
        $this->taskRepository->uncompletedTaskNameExists($newTaskName)->willReturn(false);
        $this->taskRepository->findById($task->getTaskId())->willReturn($task);
        $command = new UpdateTaskName($task->getTaskId()->value(), $newTaskName->value());
        $handler = $this->handler;

        $handler($command);

        $this->taskRepository->save(Argument::type(Task::class))->shouldHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::type(TaskNameUpdated::class))->shouldHaveBeenCalled();
    }

    public function testItDoesNotUpdateATaskWhenNameExist()
    {
        $task = Utils::createTask();
        $newTaskName = TaskName::create('updated task');
        $this->taskRepository->uncompletedTaskNameExists($newTaskName)->willReturn(true);
        $command = new UpdateTaskName($task->getTaskId()->value(), $newTaskName->value());
        $handler = $this->handler;

        $this->expectException(TaskExistsException::class);

        $handler($command);

        $this->taskRepository->findById($task->getTaskId())->shouldNotHaveBeenCalled();
        $this->taskRepository->save(Argument::cetera())->shouldNotHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    public function testThrowsExceptionWhenTaskDoesNotExist()
    {
        $newTaskName = TaskName::create('updated task');
        $this->taskRepository->uncompletedTaskNameExists($newTaskName)->willReturn(false);
        $this->taskRepository->findById(Argument::type(TaskId::class))->willThrow(NotFoundException::class);
        $command = new UpdateTaskName(1, $newTaskName->value());
        $handler = $this->handler;

        $this->expectException(NotFoundException::class);

        $handler($command);

        $this->taskRepository->save(Argument::cetera())->shouldNotHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::cetera())->shouldNotHaveBeenCalled();
    }
}
