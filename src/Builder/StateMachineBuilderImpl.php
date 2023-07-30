<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\Impl\StateMachineImpl;
use DDDPHP\StateMachine\Impl\TransitionType;
use DDDPHP\StateMachine\StateMachineFactory;
use DDDPHP\StateMachine\StateMachineInterface;

class StateMachineBuilderImpl implements StateMachineBuilderInterface
{
    private array $stateMap = [];
    private StateMachineImpl $stateMachine;

    public function __construct()
    {
        $this->stateMachine = new StateMachineImpl($this->stateMap);
    }

    public function externalTransition():  ExternalTransitionBuilderInterface
    {
        return new TransitionBuilderImpl($this->stateMap, TransitionType::EXTERNAL);
    }

    public function externalTransitions(): ExternalTransitionsBuilderInterface
    {
        return new TransitionsBuilderImpl($this->stateMap, TransitionType::EXTERNAL);
    }

    public function internalTransition(): InternalTransitionBuilderInterface
    {
        return new TransitionBuilderImpl($this->stateMap, TransitionType::INTERNAL);
    }

    public function build(string $machineId): StateMachineInterface
    {
        $this->stateMachine->setMachineId($machineId);
        $this->stateMachine->setReady(true);
        (new StateMachineFactory)->register($this->stateMachine);
        return $this->stateMachine;
    }
}
