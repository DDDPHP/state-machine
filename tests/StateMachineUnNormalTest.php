<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Tests;

use DDDPHP\StateMachine\Action\ActionInterface;
use DDDPHP\StateMachine\Builder\StateMachineBuilderFactory;
use DDDPHP\StateMachine\Condition\ConditionInterface;
use DDDPHP\StateMachine\StateMachine\StateMachineException;
use PHPUnit\Framework\TestCase;

final class StateMachineUnNormalTest extends TestCase
{
    private string $context = 'operator:frank, entityId:123465';

    public function testConditionNotMeet(){
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(StateMachineTest::STATE1)
            ->to(StateMachineTest::STATE2)
            ->on(StateMachineTest::EVENT1)
            ->when($this->checkConditionFalse())
            ->perform($this->doAction());

        $stateMachine = $builder->build("NotMeetConditionMachine");
        $target = $stateMachine->fire(StateMachineTest::STATE1, StateMachineTest::EVENT1, $this->context);
        $this->assertEquals(StateMachineTest::STATE1, $target);
    }

    public function testDuplicatedTransition(){
        $this->expectException(StateMachineException::class);
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(StateMachineTest::STATE1)
            ->to(StateMachineTest::STATE2)
            ->on(StateMachineTest::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->externalTransition()
            ->from(StateMachineTest::STATE1)
            ->to(StateMachineTest::STATE2)
            ->on(StateMachineTest::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());
    }

    public function testDuplicateMachine(){
        $this->expectException(StateMachineException::class);
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(StateMachineTest::STATE1)
            ->to(StateMachineTest::STATE2)
            ->on(StateMachineTest::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->build("DuplicatedMachine");
        $builder->build("DuplicatedMachine");
    }
    private function checkConditionFalse(): ConditionInterface
    {
        return new class () implements ConditionInterface {
            public function isSatisfied($context): bool
            {
                return false;
            }
            public function getCondition(): string
            {
                return $this->condition;
            }
            public function name(): string
            {
                return 'conditionFalse';
            }
        };
    }

    private function checkCondition(): ConditionInterface
    {
        return new class () implements ConditionInterface {
            public function isSatisfied($context): bool
            {
                return true;
            }
            public function getCondition(): string
            {
                return $this->condition;
            }
            public function name(): string
            {
                return 'condition';
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
