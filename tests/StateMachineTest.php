<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Tests;

use DDDPHP\StateMachine\ActionInterface;
use DDDPHP\StateMachine\Builder\StateMachineBuilderFactory;
use DDDPHP\StateMachine\ConditionInterface;
use PHPUnit\Framework\TestCase;

final class StateMachineTest extends TestCase
{
    public const MACHINE_ID = "TestStateMachine";

    public const STATE1 = "State1";
    public const STATE2 = "State2";
    public const STATE3 = "State3";
    public const STATE4 = "State4";

    public const EVENT1 = "EVENT1";
    public const EVENT2 = "EVENT2";
    public const EVENT3 = "EVENT3";
    public const EVENT4 = "EVENT4";
    public const INTERNAL_EVENT = "INTERNAL_EVENT";

    public static string $context = 'operator:frank, entityId:123465';

    public function testExternalNormal() {
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(self::STATE1)
            ->to(self::STATE2)
            ->on(self::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());
        $stateMachine = $builder->build(self::MACHINE_ID);
        $target = $stateMachine->fireEvent(self::STATE1, self::EVENT1, self::$context);
        $this->assertEquals(self::STATE2, $target);
    }

    private function checkCondition(): ConditionInterface
    {
        return new class () implements ConditionInterface {
            public function isSatisfied($context): bool
            {
                echo "Check condition : " . $context . "\n";
                return true;
            }
            public function name(): string
            {
                return '';
            }
        };
    }

    private function doAction(): ActionInterface
    {
        return new class () implements ActionInterface {
            public function execute($from, $to, $event, $context): void
            {
                echo $context . " from:" . $from . " to:" . $to . " on:" . $event;
            }
        };
    }
}
