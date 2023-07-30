<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine;

interface VisitableInterface
{
    public function accept(VisitorInterface $visitor): string;
}