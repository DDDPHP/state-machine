<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface VisitorInterface
{
    public const LF = '\n';

    public function visitOnEntryByStateMachine(StateMachineInterface $visitable): string;

    public function visitOnExitByStateMachine(StateMachineInterface $visitable): string;

    public function visitOnEntryByState(StateInterface $visitable): string;

    public function visitOnExitByState(StateInterface $visitable): string;
}