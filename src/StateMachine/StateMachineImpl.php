<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\StateMachine;

use DDDPHP\StateMachine\Dispatcher\AbstractSubject;
use DDDPHP\StateMachine\Event\PostTransitionEventInterface;
use DDDPHP\StateMachine\Event\PreTransitionEventInterface;
use DDDPHP\StateMachine\State\StateHelper;
use DDDPHP\StateMachine\State\StateInterface;
use DDDPHP\StateMachine\Transition\TransitionInterface;
use DDDPHP\StateMachine\Visitor\SysOutVisitor;
use DDDPHP\StateMachine\Visitor\VisitorInterface;

class StateMachineImpl extends AbstractSubject implements StateMachineInterface
{
    private string $machineId;

    private array $stateMap;

    private bool $ready;

    public function __construct(array &$stateMap)
    {
        parent::__construct();
        $this->stateMap = &$stateMap;
    }

    public function verify(string $sourceStateId, string $event): bool
    {
        $this->isReady();

        $sourceState = $this->getState($sourceStateId);
        $transitions = $sourceState->getEventTransitions($event);

        return $transitions != null && count($transitions) != 0;
    }

    private function isReady(): void
    {
        if (!$this->ready) {
            throw new StateMachineException("State machine is not built yet, can not work");
        }
    }

    private function getState(string $currentStateId): StateInterface
    {
        return StateHelper::getState($this->stateMap, $currentStateId);
    }

    public function fire(string $sourceStateId, string $event, $context): string
    {
        $this->isReady();
        $this->fireEvent(new class () implements PreTransitionEventInterface {
        });
        $transition = $this->routeTransition($sourceStateId, $event, $context);
        if ($transition == null) {
            return $sourceStateId;
        }

        $target = $transition->transit($context, false)->getId();

        $this->fireEvent(new class () implements PostTransitionEventInterface {
        });

        return $target;
    }

    private function routeTransition(string $sourceStateId, string $event, $ctx): ?TransitionInterface
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

    public function showStateMachine(): void
    {
        $sysOutVisitor = new SysOutVisitor();
        $this->accept($sysOutVisitor);
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

    public function getMachineId(): string
    {
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

    public function addTransitionPreListener(callable $listener): void
    {
        $this->addListener(
            new class () implements PreTransitionEventInterface {},
            $listener
        );
    }

    public function addTransitionPostListener(callable $listener): void
    {
        $this->addListener(
            new class () implements PostTransitionEventInterface {},
            $listener
        );
    }
}
