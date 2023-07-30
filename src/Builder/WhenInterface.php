<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;
use DDDPHP\StateMachine\ActionInterface;

interface WhenInterface
{
    public function perform(ActionInterface $action): void;
}