<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Visitor;

use DDDPHP\StateMachine\State\StateInterface;
use DDDPHP\StateMachine\StateMachine\StateMachineInterface;

interface VisitorInterface
{
    public const LF = '\n';

    public function visitOnEntryByStateMachine(StateMachineInterface $visitable): string;

    public function visitOnExitByStateMachine(StateMachineInterface $visitable): string;

    public function visitOnEntryByState(StateInterface $visitable): string;

    public function visitOnExitByState(StateInterface $visitable): string;
}
