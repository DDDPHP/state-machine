<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Dispatcher;

use DDDPHP\StateMachine\Event\EventInterface;

interface ObservableInterface
{
    /**
     * Add listener to observable subject.
     * @param EventInterface $event
     * @param callable $listener
     */
    public function addListener(EventInterface $event, callable $listener): void;

    /**
     * Fire event to notify all observers
     * @param EventInterface $event $event based event
     */
    public function fireEvent(EventInterface $event);
}
