<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface ActionInterface
{
    public function execute($from, $to, $event, $context): void;
}