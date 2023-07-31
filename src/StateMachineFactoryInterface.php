<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface StateMachineFactoryInterface
{
    public function register(StateMachineInterface $stateMachine): void;

    public function get(string $machineId): StateMachineInterface;
}
