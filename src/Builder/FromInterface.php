<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

interface FromInterface
{
    public function to(string $stateId): ToInterface;
}