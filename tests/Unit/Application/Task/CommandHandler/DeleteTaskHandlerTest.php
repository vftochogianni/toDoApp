<?php

namespace ToDo\Tests\Unit\Application\Task\CommandHandler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ToDoApp\Application\Task\Command\DeleteTask;
use ToDoApp\Application\Task\CommandHandler\DeleteTaskHandler;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Exception\NotFoundException;
use ToDoApp\Domain\Task\Event\TaskDeleted;
use ToDoApp\Domain\Task\Exception\TaskCannotBeDeletedException;
use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskRepository;
use ToDoApp\Tests\Utils;

class DeleteTaskHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|TaskRepository */
    private $taskRepository;

    /** @var ObjectProphecy|EventDispatcherInterface */
    private $dispatcher;

    private DeleteTaskHandler $handler;

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

        $this->handler = new DeleteTaskHandler($this->taskRepository->reveal(), $this->dispatcher->reveal());
    }

    public function testItDeletesATask()
    {
        $task = Utils::createTask(true);
        $this->taskRepository->findById($task->getTaskId())->willReturn($task);
        $command = new DeleteTask($task->getTaskId()->value());
        $handler = $this->handler;

        $handler($command);

        $this->taskRepository->delete(Argument::type(Task::class))->shouldHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::type(TaskDeleted::class))->shouldHaveBeenCalled();
    }

    public function testItCannotDeleteAnUncompletedTask()
    {
        $task = Utils::createTask();
        $this->taskRepository->findById($task->getTaskId())->willReturn($task);
        $command = new DeleteTask($task->getTaskId()->value());
        $handler = $this->handler;

        $this->expectException(TaskCannotBeDeletedException::class);

        $handler($command);

        $this->taskRepository->save(Argument::cetera())->shouldNotHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    public function testThrowsExceptionWhenTaskDoesNotExist()
    {
        $this->taskRepository->findById(Argument::type(TaskId::class))->willThrow(NotFoundException::class);
        $command = new DeleteTask(1);
        $handler = $this->handler;

        $this->expectException(NotFoundException::class);

        $handler($command);

        $this->taskRepository->save(Argument::cetera())->shouldNotHaveBeenCalled();
        $this->dispatcher->dispatch(Argument::cetera())->shouldNotHaveBeenCalled();
    }
}
