<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Builder;

interface ExternalTransitionsBuilderInterface
{
    public function fromAmong(string ...$stateIds): FromInterface;
}