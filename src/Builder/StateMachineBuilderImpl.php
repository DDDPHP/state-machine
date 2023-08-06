<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\StateMachine\StateMachineFactory;
use DDDPHP\StateMachine\StateMachine\StateMachineFactoryInterface;
use DDDPHP\StateMachine\StateMachine\StateMachineImpl;
use DDDPHP\StateMachine\StateMachine\StateMachineInterface;
use DDDPHP\StateMachine\Transition\TransitionType;

class StateMachineBuilderImpl implements StateMachineBuilderInterface
{
    private array $stateMap = [];
    private StateMachineImpl $stateMachine;

    private StateMachineFactoryInterface $stateMachineFactory;

    public function __construct()
    {
        $this->stateMachine = new StateMachineImpl($this->stateMap);
        $this->stateMachineFactory = new StateMachineFactory();
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
        $this->stateMachineFactory->register($this->stateMachine);
        return $this->stateMachine;
    }

    public function getFactory(): StateMachineFactoryInterface
    {
        return $this->stateMachineFactory;
    }
}
