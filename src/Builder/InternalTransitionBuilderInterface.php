<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

interface InternalTransitionBuilderInterface
{
    public function within(string $stateId): ToInterface;
}