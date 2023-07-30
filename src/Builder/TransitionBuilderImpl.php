<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\ActionInterface;
use DDDPHP\StateMachine\Builder\ExternalTransitionBuilderInterface;
use DDDPHP\StateMachine\Builder\FromInterface;
use DDDPHP\StateMachine\Builder\InternalTransitionBuilderInterface;
use DDDPHP\StateMachine\Builder\OnInterface;
use DDDPHP\StateMachine\Builder\ToInterface;
use DDDPHP\StateMachine\Builder\WhenInterface;
use DDDPHP\StateMachine\ConditionInterface;
use DDDPHP\StateMachine\Impl\StateHelper;
use DDDPHP\StateMachine\StateInterface;
use DDDPHP\StateMachine\TransitionInterface;

class TransitionBuilderImpl implements ExternalTransitionBuilderInterface, InternalTransitionBuilderInterface, FromInterface, OnInterface, ToInterface
{
    protected array $stateMap;

    private StateInterface $source;

    protected StateInterface $target;

    private TransitionInterface $transition;

    protected int $transitionType;

    public function __construct(array &$stateMap, int $transitionType) {
        $this->stateMap = &$stateMap;
        $this->transitionType = $transitionType;
    }

    public function from(string $stateId): FromInterface
    {
        $this->source = StateHelper::getState($this->stateMap, $stateId);
        return $this;
    }

    public function to(string $stateId): ToInterface
    {
        $this->target = StateHelper::getState($this->stateMap, $stateId);
        return $this;
    }

    public function within(string $stateId): ToInterface
    {
        $this->source = $this->target = StateHelper::getState($this->stateMap, $stateId);
        return $this;
    }

    public function when(ConditionInterface $condition): WhenInterface
    {
        $this->transition->setCondition($condition);
        return $this;
    }

    public function on($event): OnInterface
    {
        $this->transition = $this->source->addTransition($event, $this->target, $this->transitionType);
        return $this;
    }

    public function perform(ActionInterface $action): void
    {
        $this->transition->setAction($action);
    }
}
