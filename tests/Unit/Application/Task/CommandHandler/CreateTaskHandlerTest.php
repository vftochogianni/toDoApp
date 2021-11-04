<?php

namespace ToDo\Tests\Unit\Application\Task\CommandHandler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ToDoApp\Application\Task\Command\CreateTask;
use ToDoApp\Application\Task\CommandHandler\CreateTaskHandler;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Task\Event\TaskCreated;
use ToDoApp\Domain\Task\Exception\TaskExistsException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;
use ToDoApp\Domain\Task\TaskRepository;

class CreateTaskHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|TaskRepository */
    private $taskRepository;

    /** @var ObjectProphecy|EventDispatcherInterface */
    private $dispatcher;

    private CreateTaskHandler $handler;

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

        $this->handler = new CreateTaskHandler($this->taskRepository->reveal(), $this->dispatcher->reveal());
    }

    public function testItCreatesATask()
    {
        $taskId = TaskId::create(1);
        $taskName = TaskName::create('a new task');
        $this->taskRepository->uncompletedTaskNameExists($taskName)->willReturn(false);
        $this->taskRepository->getNextId()->willReturn($taskId);
        $command = new CreateTask($taskName->value());
        $handler = $this->handler;

        $result = $handler($command);

        self::assertInstanceOf(TaskId::class, $result);
        self::assertEquals($taskId, $result);
        $this->taskRepository->save(Argument::type(Task::class))->shouldHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::type(TaskCreated::class))->shouldHaveBeenCalled();
    }

    public function testItDoesNotCreateATaskWhenNameExist()
    {
        $taskName = TaskName::create('a new task');
        $this->taskRepository->uncompletedTaskNameExists($taskName)->willReturn(true);
        $command = new CreateTask($taskName->value());
        $handler = $this->handler;

        $this->expectException(TaskExistsException::class);

        $handler($command);

        $this->taskRepository->getNextId()->shouldNotHaveBeenCalled();
        $this->taskRepository->save(Argument::cetera())->shouldNotHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::cetera())->shouldNotHaveBeenCalled();
    }
}
