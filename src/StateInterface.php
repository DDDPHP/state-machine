<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface StateInterface
{
    public function getId();

    public function addTransition(string $event, StateInterface $target, int $transitionType): TransitionInterface;

    public function getEventTransitions(string $event): array;

    public function getAllTransitions(): array;
}
