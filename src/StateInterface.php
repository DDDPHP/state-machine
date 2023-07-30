<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface StateInterface
{
    public function getId();

    public function addTransition($event, StateInterface $target, int $transitionType): TransitionInterface;

    public function getEventTransitions($event): array;

    public function getAllTransitions(): array;
}