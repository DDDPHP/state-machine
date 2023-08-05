<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Dispatcher;

use DDDPHP\StateMachine\Event\EventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

abstract class AbstractSubject implements ObservableInterface, ListenerProviderInterface
{
    private array $listeners = [];

    private EventDispatcherInterface $eventDispatcher;

    public function __construct()
    {
        $this->eventDispatcher = new Dispatcher($this);
    }
    public function addListener(EventInterface $event, callable $listener): void
    {
        $this->listeners[$event::class][] = $listener;
    }

    public function fireEvent(EventInterface $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }

    public function getListenersForEvent(object $event): iterable
    {
        yield from $this->getForEvents($event::class);
        /** @psalm-suppress MixedArgument */
        yield from $this->getForEvents(...array_values(class_parents($event)));
        /** @psalm-suppress MixedArgument */
        yield from $this->getForEvents(...array_values(class_implements($event)));
    }

    /**
     * Get listeners for event class names specified.
     *
     * @param string ...$eventClassNames Event class names.
     *
     * @return iterable<callable> Listeners.
     */
    public function getForEvents(string ...$eventClassNames): iterable
    {
        foreach ($eventClassNames as $eventClassName) {
            if (isset($this->listeners[$eventClassName])) {
                yield from $this->listeners[$eventClassName];
            }
        }
    }
}
