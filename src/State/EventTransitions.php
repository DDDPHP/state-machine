<?php

declare(strict_types=1);ect

namespace DDDPHP\StateMachine\State;

use DDDPHP\StateMachine\StateMachine\StateMachineException;
use DDDPHP\StateMachine\Transition\TransitionInterface;

class EventTransitions
{
    private array $eventTransitions;

    public function __construct()
    {
        $this->eventTransitions = [];
    }

    public function put(string $event, TransitionInterface $transition): void
    {
        if (empty($this->eventTransitions[$event]) === false) {
            $this->verify($this->eventTransitions[$event], $transition);
        }
        $this->eventTransitions[$event][] = $transition;
    }

    private function verify(array $existingTransitions, TransitionInterface $newTransition): void
    {
        foreach ($existingTransitions as $transition) {
            if ($transition->equals($newTransition)) {
                throw new StateMachineException(" transition already exists, you can not add another one");
            }
        }
    }

    public function get(string $event): array
    {
        return $this->eventTransitions[$event] ?? [];
    }

    public function allTransitions(): array
    {
        $allTransitions = [];
        foreach ($this->eventTransitions as $transitions) {
            foreach ($transitions as $transition) {
                $allTransitions[] = $transition;
            }
        }
        return $allTransitions;
    }
}
