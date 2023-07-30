<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface ConditionInterface
{
    public function isSatisfied($context): bool;
    public function name(): string;
}