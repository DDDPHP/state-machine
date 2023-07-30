<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\ConditionInterface;

interface OnInterface extends WhenInterface
{
    public function when(ConditionInterface $condition): WhenInterface;
}