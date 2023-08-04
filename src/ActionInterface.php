<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface ActionInterface
{
    public function execute(string $from, string $to, string $event, $context): void;
}