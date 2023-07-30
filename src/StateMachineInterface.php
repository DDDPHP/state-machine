<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface StateMachineInterface extends VisitableInterface
{
    public function verify(string $sourceStateId, $event): bool;

    public function fireEvent(string $sourceStateId, $event, $context): string;

    public function getMachineId(): string;

    public function showStateMachine(): void;
}
