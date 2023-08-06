<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\State;

use DDDPHP\StateMachine\Transition\TransitionImpl;
use DDDPHP\StateMachine\Transition\TransitionInterface;
use DDDPHP\StateMachine\Visitor\VisitorInterface;

class StateImpl implements StateInterface
{
    protected string $stateId;

    private EventTransitions $eventTransitions;
    public function __construct(string $stateId)
    {
        $this->stateId = $stateId;
        $this->eventTransitions = new EventTransitions();
    }

    public function addTransition(string $event, StateInterface $target, int $transitionType): TransitionInterface
    {
        $newTransition = new TransitionImpl;
        $newTransition->setSource($this);
        $newTransition->setTarget($target);
        $newTransition->setEvent($event);
        $newTransition->setType($transitionType);
        $this->eventTransitions->put($event, $newTransition);
        return $newTransition;
    }

    public function getEventTransitions(string $event): array
    {
        return $this->eventTransitions->get($event);
    }

    public function getAllTransitions(): array
    {
        return $this->eventTransitions->allTransitions();
    }

    public function getId(): string
    {
        return $this->stateId;
    }

    public function accept(VisitorInterface $visitor): string
    {
        $entryString = $visitor->visitOnEntryByState($this);
        $exitString = $visitor->visitOnExitByState($this);
        return $entryString . $exitString;
    }

    public function equals(Object $anObject): bool
    {
        if($anObject instanceof StateInterface){
            if($this->stateId === $anObject->getId())
                return true;
        }
        return false;
    }

    public function __toString(): string
    {
        return $this->stateId;
    }
}
