<?php

namespace ToDoApp\Domain;

abstract class Aggregate
{
    protected array $events = [];

    public function record(DomainEvent $event)
    {
        $this->events[] = $event;
    }

    public function resetEvents()
    {
        $this->events = [];
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
