<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\Action\ActionInterface;
use DDDPHP\StateMachine\Condition\ConditionInterface;
use DDDPHP\StateMachine\State\StateHelper;
use DDDPHP\StateMachine\State\StateInterface;
use DDDPHP\StateMachine\Transition\TransitionInterface;

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

    public function on(string $event): OnInterface
    {
        $this->transition = $this->source->addTransition($event, $this->target, $this->transitionType);
        return $this;
    }

    public function perform(ActionInterface $action): void
    {
        $this->transition->setAction($action);
    }
}
