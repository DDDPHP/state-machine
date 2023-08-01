<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Impl;

use DDDPHP\StateMachine\StateInterface;
use DDDPHP\StateMachine\StateMachineInterface;
use DDDPHP\StateMachine\VisitorInterface;

class SysOutVisitor implements VisitorInterface
{
    public function visitOnEntryByStateMachine(StateMachineInterface $stateMachine): string
    {
        $onEntry = "-----StateMachine:".$stateMachine->getMachineId()."-------";
        $onEntry .= self::LF;
        return $onEntry;
    }

    public function visitOnExitByStateMachine(StateMachineInterface $stateMachine): string
    {
        $onExit = "------------------------";
        $onExit .= self::LF;
        return $onExit;
    }

    public function visitOnEntryByState(StateInterface $state): string
    {
        $stateStr = "State:" . $state->getId();
        $stateStr .= self::LF;
        $resultStr = $stateStr;
        echo $stateStr;
        $transitionStr = '';
        foreach ($state->getAllTransitions() as $transition){
            $transitionStr .= ("    Transition:".$transition);
            $transitionStr .= self::LF;
            echo $transitionStr;
        }
        $resultStr .= $transitionStr;
        return $resultStr;
    }

    public function visitOnExitByState(StateInterface $state): string
    {
        $stateStr = "End State:" . $state->getId();
        $stateStr .= self::LF;
        echo $stateStr;
        return $stateStr;
    }
}
