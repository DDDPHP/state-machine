<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Visitor;

use DDDPHP\StateMachine\State\StateInterface;
use DDDPHP\StateMachine\StateMachine\StateMachineInterface;

class SysOutVisitor implements VisitorInterface
{
    public function visitOnEntryByStateMachine(StateMachineInterface $visitable): string
    {
        $onEntry = "-----StateMachine:".$visitable->getMachineId()."-------";
        $onEntry .= self::LF;
        return $onEntry;
    }

    public function visitOnExitByStateMachine(StateMachineInterface $visitable): string
    {
        $onExit = "------------------------";
        $onExit .= self::LF;
        return $onExit;
    }

    public function visitOnEntryByState(StateInterface $visitable): string
    {
        $stateStr = "State:" . $visitable->getId();
        $stateStr .= self::LF;
        $resultStr = $stateStr;
        echo $stateStr;
        $transitionStr = '';
        foreach ($visitable->getAllTransitions() as $transition){
            $transitionStr .= ("    Transition:".$transition);
            $transitionStr .= self::LF;
            echo $transitionStr;
        }
        $resultStr .= $transitionStr;
        return $resultStr;
    }

    public function visitOnExitByState(StateInterface $visitable): string
    {
        $stateStr = "End State:" . $visitable->getId();
        $stateStr .= self::LF;
        echo $stateStr;
        return $stateStr;
    }
}
