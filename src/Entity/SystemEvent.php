<?php

namespace ToDoApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use ToDoApp\Domain\DomainEvent;
use ToDoApp\Repository\SystemEventRepository;

/**
 * @ORM\Entity(repositoryClass=SystemEventRepository::class)
 * @HasLifecycleCallbacks
 */
class SystemEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $taskId;

    /**
     * @ORM\Column(type="json")
     */
    private $payload = [];

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $recordedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getRecordedAt(): ?\DateTimeImmutable
    {
        return $this->recordedAt;
    }

    /**
     * @PrePersist
     */
    public function setRecordedAt(): self
    {
        $this->recordedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setTaskId($taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    public static function fromDomainEvent(DomainEvent $event): self
    {
        $self = new self();
        $self->setName($event->getName())->setPayload($event->getPayload())->setTaskId($event->getTaskId());

        return $self;
    }
}
