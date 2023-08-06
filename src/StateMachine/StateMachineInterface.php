<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\StateMachine;

use DDDPHP\StateMachine\Visitor\VisitableInterface;

interface StateMachineInterface extends VisitableInterface
{
    public function verify(string $sourceStateId, string $event): bool;

    public function fire(string $sourceStateId, string $event, $context): string;

    public function getMachineId(): string;

    public function showStateMachine(): void;

    public function addTransitionPreListener(callable $listener): void;

    public function addTransitionPostListener(callable $listener): void;
}
