<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

use DDDPHP\StateMachine\Action\ActionInterface;
use DDDPHP\StateMachine\Condition\ConditionInterface;
use DDDPHP\StateMachine\State\StateHelper;

class TransitionsBuilderImpl extends TransitionBuilderImpl implements ExternalTransitionsBuilderInterface
{
    private array $sources;

    private array $transitions;

    public function __construct(array &$stateMap, int $transitionType) {
        parent::__construct($stateMap, $transitionType);
    }

    public function fromAmong(string ...$stateIds): FromInterface
    {
        foreach ($stateIds as $stateId) {
            $this->sources[] = StateHelper::getState($this->stateMap, $stateId);
        }
        return $this;
    }

    public function on(string $event): OnInterface{
        foreach ($this->sources as $source) {
            $this->transitions[] = $source->addTransition($event, $this->target, $this->transitionType);
        }
        return $this;
    }

    public function when(ConditionInterface $condition): WhenInterface{
        foreach ($this->transitions as &$transition) {
            $transition->setCondition($condition);
        }
        return $this;
    }

    public function perform(ActionInterface $action): void
    {
        foreach ($this->transitions as &$transition) {
            $transition->setAction($action);
        }
    }
}
