<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Impl;

use DDDPHP\StateMachine\ActionInterface;
use DDDPHP\StateMachine\ConditionInterface;
use DDDPHP\StateMachine\StateInterface;
use DDDPHP\StateMachine\TransitionInterface;

class TransitionImpl implements TransitionInterface
{
    private StateInterface $source;
    private StateInterface $target;
    private string $event;
    private ConditionInterface $condition;
    private ActionInterface $action;
    private int $type = TransitionType::EXTERNAL;

    public function getSource(): StateInterface
    {
        return $this->source;
    }

    public function setSource(StateInterface $state): void
    {
        $this->source = $state;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function setEvent($event): void
    {
        $this->event = $event;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getTarget(): StateInterface
    {
        return $this->target;
    }

    public function setTarget(StateInterface $target): void
    {
        $this->target = $target;
    }

    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }

    public function setCondition(ConditionInterface $condition): void
    {
        $this->condition = $condition;
    }

    public function getAction(): ActionInterface
    {
        return $this->action;
    }

    public function setAction(ActionInterface $action): void
    {
        $this->action = $action;
    }

    public function transit($ctx, bool $checkCondition): StateInterface
    {
        $this->verify();
        if (!$checkCondition || $this->condition->isSatisfied($ctx)) {
            $this->action->execute($this->source->getId(), $this->target->getId(), $this->event, $ctx);
            return $this->target;
        }
        return $this->source;
    }

    public function __toString(): string
    {
        return $this->source->getId() . "-[" . $this->event .", ".$this->type."]->" . $this->target->getId();
    }

    public function equals(TransitionInterface $anObject): bool
    {
        if($this->event === $anObject->getEvent()
                && $this->source === $anObject->getSource()
                && $this->target === $anObject->getTarget())
        {
            return true;
        }
        return false;
    }

    public function verify():void
    {
        if($this->type == TransitionType::INTERNAL && $this->source != $this->target) {
            throw new StateMachineException(
                sprintf(
                    'Internal transition source state "%s" and target state "%s" must be same.',
                    $this->source,
                    $this->target
                )
            );
        }
    }
}
