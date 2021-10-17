<?php

namespace ToDoApp\Application;

use ToDoApp\Domain\Aggregate;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventAwareHandler
{
    protected EventDispatcherInterface $eventDispatcher;

    public function recordFor(Aggregate $aggregate)
    {
        $events = $aggregate->getEvents();
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
