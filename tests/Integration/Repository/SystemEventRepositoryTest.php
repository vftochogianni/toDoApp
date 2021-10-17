<?php

namespace ToDoApp\Tests\Integration\Repository;

use ToDoApp\Entity\SystemEvent;
use ToDoApp\Repository\SystemEventRepository;
use ToDoApp\Tests\Integration\IntegrationTestCase;

class SystemEventRepositoryTest extends IntegrationTestCase
{
    public function testSaveSystemEvent()
    {
        $id = uniqid();
        $userId = uniqid();
        /** @var SystemEventRepository $repository */
        $repository = $this->entityManager->getRepository(SystemEvent::class);
        $systemEvent = (new SystemEvent())
            ->setPayload(['userId' => $id])
            ->setName('test.event')
            ->setRecordedAt()
            ->setUserId($userId);

        $repository->save($systemEvent);

        /** @var SystemEvent $result */
        $result = $repository->findOneBy(['id' => $systemEvent->getId()]);
        self::assertEquals($systemEvent, $result);
        self::assertEquals('test.event', $result->getName());
        self::assertEquals($userId, $result->getUserId());
        self::assertEquals(['userId' => $id], $result->getPayload());
    }
}
