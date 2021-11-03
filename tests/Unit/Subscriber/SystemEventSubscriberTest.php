<?php

namespace ToDoApp\Tests\Unit\Subscriber;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LoggerInterface;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Domain\Task\Event\TaskCompleted;
use ToDoApp\Domain\Task\Event\TaskCreated;
use ToDoApp\Domain\Task\Event\TaskDeleted;
use ToDoApp\Domain\Task\Event\TaskNameUpdated;
use ToDoApp\Entity\SystemEvent;
use ToDoApp\Repository\SystemEventRepository;
use ToDoApp\Subscriber\SystemEventSubscriber;

class SystemEventSubscriberTest extends TestCase
{
    use ProphecyTrait;

    /** @var SystemEventSubscriber */
    private $subscriber;

    /** @var \Prophecy\Prophecy\ObjectProphecy|SystemEventRepository */
    private $repository;

    protected function setUp(): void
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $this->repository = $this->prophesize(SystemEventRepository::class);
        $mr = $this->prophesize(ManagerRegistry::class);
        $mr->getRepository(SystemEvent::class)->willReturn($this->repository->reveal());

        $this->subscriber = new SystemEventSubscriber($logger->reveal(), $mr->reveal());
    }

    public function testOnDomainEventItStoresSystemEvent()
    {
        $this->subscriber->onDomainEvent($this->getDomainEvent());

        $this->repository->save(Argument::type(SystemEvent::class))->shouldHaveBeenCalled();
    }

    /**
     * @dataProvider eventList
     */
    public function testGetSubscribedEvents(string $eventName)
    {
        self::assertArrayHasKey($eventName, SystemEventSubscriber::getSubscribedEvents());
    }

    public function eventList(): array
    {
        return [
            [TaskCreated::class],
            [TaskNameUpdated::class],
            [TaskCompleted::class],
            [TaskDeleted::class],
        ];
    }

    private function getDomainEvent(): DomainEvent
    {
        return new class() extends DomainEvent {
            public function getPayload(): array
            {
                return [];
            }

            public function getName(): string
            {
                return 'test.event';
            }

            public function getTaskId(): int
            {
                return 1;
            }

            public function recordedAt(): \DateTimeImmutable
            {
                return new \DateTimeImmutable();
            }

            public function isPropagationStopped(): bool
            {
                return true;
            }

            public function stopPropagation(): void
            {
                return;
            }
        };
    }
}
