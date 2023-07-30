<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;
use DDDPHP\StateMachine\StateMachineInterface;

interface StateMachineBuilderInterface
{
    public function externalTransition(): ExternalTransitionBuilderInterface;

    public function externalTransitions(): ExternalTransitionsBuilderInterface;

    public function internalTransition(): InternalTransitionBuilderInterface;

    public function build(string $machineId): StateMachineInterface;
}