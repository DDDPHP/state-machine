<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface StateContextInterface
{
    public function getTransition(): TransitionInterface;

    public function getStateMachine(): StateMachineInterface;
}