<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Tests;

use DDDPHP\StateMachine\ActionInterface;
use DDDPHP\StateMachine\Builder\StateMachineBuilderFactory;
use DDDPHP\StateMachine\ConditionInterface;
use PHPUnit\Framework\TestCase;

final class StateMachineChoiceTest extends TestCase
{
    private function getContext($con): object
    {
        return new class ($con) {
            private string $condition;
            public function __construct(string $condition)
            {
                $this->condition = $condition;
            }
            public function getCondition(): string
            {
                return $this->condition;
            }
            public function setCondition(string $condition): void
            {
                $this->condition = $condition;
            }
            public function __toString(): string
            {
                return $this->condition . 'contextClass';
            }
        };
    }
    /**
     * Test Choice, for the event：EVENT1
     * if condition == "1", STATE1 --> STATE1
     * if condition == "2" , STATE1 --> STATE2
     * if condition == "3" , STATE1 --> STATE3
     */
    public function testChoice(): void
    {

        $builder = StateMachineBuilderFactory::create();

        $builder->internalTransition()
            ->within(StateMachineTest::STATE1)
            ->on(StateMachineTest::EVENT1)
            ->when($this->checkCondition1())
            ->perform($this->doAction());

        $builder->externalTransition()
           ->from(StateMachineTest::STATE1)
           ->to(StateMachineTest::STATE2)
           ->on(StateMachineTest::EVENT1)
           ->when($this->checkCondition2())
           ->perform($this->doAction());

        $builder->externalTransition()
            ->from(StateMachineTest::STATE1)
            ->to(StateMachineTest::STATE3)
            ->on(StateMachineTest::EVENT1)
            ->when($this->checkCondition3())
            ->perform($this->doAction());

        $stateMachine = $builder->build("ChoiceConditionMachine");

        $target1 = $stateMachine->fire(
            StateMachineTest::STATE1,
            StateMachineTest::EVENT1,
            $this->getContext("1")
        );
        $this->assertEquals(StateMachineTest::STATE1, $target1);

        $target2 = $stateMachine->fire(
            StateMachineTest::STATE1,
            StateMachineTest::EVENT1,
            $this->getContext("2")
        );
        $this->assertEquals(StateMachineTest::STATE2, $target2);

        $target3 = $stateMachine->fire(
            StateMachineTest::STATE1,
            StateMachineTest::EVENT1,
            $this->getContext("3")
        );
        $this->assertEquals(StateMachineTest::STATE3, $target3);
    }

    private function checkCondition1(): ConditionInterface
    {
        return new class () implements ConditionInterface {
            public function isSatisfied($context): bool
            {
                return '1' === $context->getCondition();
            }
            public function name(): string
            {
                return 'condition1';
            }
        };
    }

    private function checkCondition2(): ConditionInterface
    {
        return new class () implements ConditionInterface {
            public function isSatisfied($context): bool
            {
                return '2' === $context->getCondition();
            }
            public function name(): string
            {
                return 'condition2';
            }
        };
    }

    private function checkCondition3(): ConditionInterface
    {
        return new class () implements ConditionInterface {
            public function isSatisfied($context): bool
            {
                return '3' === $context->getCondition();
            }
            public function name(): string
            {
                return 'condition3';
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
