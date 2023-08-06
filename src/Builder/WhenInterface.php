<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;
use DDDPHP\StateMachine\Action\ActionInterface;

interface WhenInterface
{
    public function perform(ActionInterface $action): void;
}
