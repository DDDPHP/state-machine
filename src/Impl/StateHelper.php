<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Impl;

use DDDPHP\StateMachine\StateInterface;

class StateHelper
{
    public static function getState(array &$stateMap, $stateId): StateInterface
    {
        if (empty($stateMap[$stateId]) === true) {
            $state = new StateImpl($stateId);
            $stateMap[$stateId] = $state;
        } else {
            $state = $stateMap[$stateId];
        }
        return $state;
    }
}
