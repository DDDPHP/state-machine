<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\StateMachine;

class StateMachineFactory implements StateMachineFactoryInterface
{
    private array $stateMachineMap = array();

    public function register(StateMachineInterface $stateMachine): void
    {
        $machineId = $stateMachine->getMachineId();
        if(array_key_exists($machineId, $this->stateMachineMap) === true){
            throw new StateMachineException("The state machine with id [".$machineId."] is already built, no need to build again");
        }
        $this->stateMachineMap[$stateMachine->getMachineId()] = $stateMachine;
    }

    public function get(string $machineId): StateMachineInterface
    {
        if(array_key_exists($machineId, $this->stateMachineMap) === false) {
            throw new StateMachineException("There is no stateMachine instance for ".$machineId.", please build it first");
        }
        return $this->stateMachineMap[$machineId];
    }
}
