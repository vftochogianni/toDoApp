<?php

namespace ToDoApp\Tests\Integration;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IntegrationTestCase extends KernelTestCase
{
    protected ?EntityManager $entityManager;

    public function setUp(): void
    {
        $_ENV['KERNEL_CLASS'] = 'ToDoApp\Kernel';
        $_ENV['DATABASE_URL'] = 'mysql://root:secret@todoapp-mariadb-service:3306/todoapp?serverVersion=5.7';

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
