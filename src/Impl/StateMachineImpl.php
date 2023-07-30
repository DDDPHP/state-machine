<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Impl;

use DDDPHP\StateMachine\StateInterface;
use DDDPHP\StateMachine\StateMachineInterface;
use DDDPHP\StateMachine\TransitionInterface;
use DDDPHP\StateMachine\VisitorInterface;

class StateMachineImpl implements StateMachineInterface
{
    private string $machineId;

    private array $stateMap;

    private bool $ready;

    public function __construct(array &$stateMap)
    {
        $this->stateMap = &$stateMap;
    }

    public function verify(string $sourceStateId, $event): bool
    {
        $this->isReady();

        $sourceState = $this->getState($sourceStateId);
        $transitions = $sourceState->getEventTransitions($event);

        return $transitions != null && count($transitions) != 0;
    }

    public function fireEvent(string $sourceStateId, $event, $ctx): string
    {
        $this->isReady();
        $transition = $this->routeTransition($sourceStateId, $event, $ctx);
        if ($transition == null) {
            return $sourceStateId;
        }

        return $transition->transit($ctx, false)->getId();
    }

    private function routeTransition(string $sourceStateId, $event, $ctx): ?TransitionInterface
    {
        $sourceState = $this->getState($sourceStateId);

        $transitions = $sourceState->getEventTransitions($event);
        if ($transitions == null || count($transitions) === 0) {
            return null;
        }

        $transit = null;
        foreach ($transitions as $transition) {
            if ($transition->getCondition() === null) {
                $transit = $transition;
            } else if ($transition->getCondition()->isSatisfied($ctx)) {
                $transit = $transition;
                break;
            }
        }

        return $transit;
    }

    private function getState(string $currentStateId): StateInterface
    {
        $state = StateHelper::getState($this->stateMap, $currentStateId);
        return $state;
    }

    private function isReady(): void
    {
        if (!$this->ready) {
            throw new StateMachineException("State machine is not built yet, can not work");
        }
    }

    public function accept(VisitorInterface $visitor): string
    {
        $showResult = $visitor->visitOnEntryByStateMachine($this);
        foreach ($this->stateMap as $state) {
            $showResult .= $state->accept($visitor);
        }
        $showResult .= $visitor->visitOnExitByStateMachine($this);
        return $showResult;
    }

    public function showStateMachine(): void
    {
        $sysOutVisitor = new SysOutVisitor();
        $this->accept($sysOutVisitor);
    }

    public function getMachineId(): string{
        return $this->machineId;
    }

    public function setMachineId(string $machineId): void
    {
        $this->machineId = $machineId;
    }

    public function setReady(bool $ready): void
    {
        $this->ready = $ready;
    }
}
