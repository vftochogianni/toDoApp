<?php

namespace ToDo\Tests\Unit\Application\Task\CommandHandler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ToDoApp\Application\Task\Command\CompleteTask;
use ToDoApp\Application\Task\CommandHandler\CompleteTaskHandler;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Exception\NotFoundException;
use ToDoApp\Domain\Task\Event\TaskCompleted;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskRepository;
use ToDoApp\Tests\Utils;

class CompleteTaskHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|TaskRepository */
    private $taskRepository;

    /** @var ObjectProphecy|EventDispatcherInterface */
    private $dispatcher;

    private CompleteTaskHandler $handler;

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

        $this->handler = new CompleteTaskHandler($this->taskRepository->reveal(), $this->dispatcher->reveal());
    }

    public function testItCompletesATask()
    {
        $task = Utils::createTask();
        $this->taskRepository->findById($task->getTaskId())->willReturn($task);
        $command = new CompleteTask($task->getTaskId()->value());
        $handler = $this->handler;

        $handler($command);

        $this->taskRepository->save(Argument::type(Task::class))->shouldHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::type(TaskCompleted::class))->shouldHaveBeenCalled();
    }

    public function testThrowsExceptionWhenTaskDoesNotExist()
    {
        $this->taskRepository->findById(Argument::type(TaskId::class))->willThrow(NotFoundException::class);
        $command = new CompleteTask(1);
        $handler = $this->handler;

        $this->expectException(NotFoundException::class);

        $handler($command);

        $this->taskRepository->save(Argument::cetera())->shouldNotHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::cetera())->shouldNotHaveBeenCalled();
    }
}
