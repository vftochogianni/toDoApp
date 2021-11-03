<?php

namespace ToDoApp\Tests\Integration\Repository;

use ToDoApp\Entity\SystemEvent;
use ToDoApp\Repository\SystemEventRepository;
use ToDoApp\Tests\Integration\IntegrationTestCase;

class SystemEventRepositoryTest extends IntegrationTestCase
{
    public function testSaveSystemEvent()
    {
        $taskId = uniqid();
        /** @var SystemEventRepository $repository */
        $repository = $this->entityManager->getRepository(SystemEvent::class);
        $systemEvent = (new SystemEvent())
            ->setPayload(['taskId' => $taskId])
            ->setName('test.event')
            ->setRecordedAt()
            ->setTaskId($taskId);

        $repository->save($systemEvent);

        /** @var SystemEvent $result */
        $result = $repository->findOneBy(['id' => $systemEvent->getId()]);
        self::assertEquals($systemEvent, $result);
        self::assertEquals('test.event', $result->getName());
        self::assertEquals($taskId, $result->getTaskId());
        self::assertEquals(['taskId' => $taskId], $result->getPayload());
    }
}
