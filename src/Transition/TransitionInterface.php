<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Transition;

use DDDPHP\StateMachine\Action\ActionInterface;
use DDDPHP\StateMachine\Condition\ConditionInterface;
use DDDPHP\StateMachine\State\StateInterface;

interface TransitionInterface
{
    public function getSource(): StateInterface;

    public function setSource(StateInterface $state);

    public function getEvent(): string;

    public function setEvent(string $event): void;

    public function setType(int $type): void;

    public function getTarget();

    public function setTarget(StateInterface $state): void;

    public function getCondition(): ConditionInterface;

    public function setCondition(ConditionInterface $condition): void;

    public function getAction(): ActionInterface;

    public function setAction(ActionInterface $action);

    public function transit($ctx, bool $checkCondition): StateInterface;

    public function verify(): void;
}
