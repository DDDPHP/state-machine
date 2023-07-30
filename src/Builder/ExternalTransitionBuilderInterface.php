<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

interface ExternalTransitionBuilderInterface
{
    public function from(string $stateId): FromInterface;
}