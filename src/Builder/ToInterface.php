<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

interface ToInterface
{
    public function on($event): OnInterface;
}