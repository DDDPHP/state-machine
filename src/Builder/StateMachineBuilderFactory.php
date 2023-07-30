<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\Builder\StateMachineBuilderImpl;

class StateMachineBuilderFactory
{
    public static function create(): StateMachineBuilderInterface
    {
        return new StateMachineBuilderImpl();
    }
}