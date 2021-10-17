<?php

namespace ToDoApp\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ToDoApp\Entity\SystemEvent;

/**
 * @method SystemEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method SystemEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method SystemEvent[]    findAll()
 * @method SystemEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SystemEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SystemEvent::class);
    }

    public function save(SystemEvent $event)
    {
        $this->_em->persist($event);
        $this->_em->flush();
    }
}
