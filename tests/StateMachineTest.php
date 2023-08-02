<?php

declare(strict_types=1);

namespace DDDPHP\StateMachine\Tests;

use DDDPHP\StateMachine\ActionInterface;
use DDDPHP\StateMachine\Builder\StateMachineBuilderFactory;
use DDDPHP\StateMachine\ConditionInterface;
use DDDPHP\StateMachine\StateMachineInterface;
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

    private string $context = 'operator:frank, entityId:123465';

    public function testExternalNormal(): void
    {
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(self::STATE1)
            ->to(self::STATE2)
            ->on(self::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());
        $stateMachine = $builder->build(self::MACHINE_ID);

        $target = $stateMachine->fire(self::STATE1, self::EVENT1, $this->context);
        $this->assertEquals(self::STATE2, $target);
    }

    public function testVerify(): void
    {
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(self::STATE1)
            ->to(self::STATE2)
            ->on(self::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());
        $stateMachine = $builder->build(self::MACHINE_ID . "-testVerify");

        $this->assertTrue($stateMachine->verify(self::STATE1, self::EVENT1));
        $this->assertFalse($stateMachine->verify(self::STATE1, self::EVENT2));
    }

    public function testExternalTransitionsNormal(): void
    {
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransitions()
            ->fromAmong(self::STATE1, self::STATE2, self::STATE3)
            ->to(self::STATE4)
            ->on(self::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());
        $stateMachine = $builder->build(self::MACHINE_ID . "1");

        $target = $stateMachine->fire(self::STATE2, self::EVENT1, $this->context);
        $this->assertEquals(self::STATE4, $target);
    }

    public function testInternalNormal(): void
    {
        $builder = StateMachineBuilderFactory::create();
        $builder->internalTransition()
            ->within(self::STATE1)
            ->on(self::INTERNAL_EVENT)
            ->when($this->checkCondition())
            ->perform($this->doAction());
        $stateMachine = $builder->build(self::MACHINE_ID . "2");

        $stateMachine->fire(self::STATE1, self::EVENT1, $this->context);
        $target = $stateMachine->fire(self::STATE1, self::INTERNAL_EVENT, $this->context);
        $this->assertEquals(self::STATE1, $target);
    }

    public function testExternalInternalNormal(): void
    {
        $stateMachine = $this->buildStateMachine("testExternalInternalNormal");

        $context = $this->context;
        $target = $stateMachine->fire(self::STATE1, self::EVENT1, $context);
        $this->assertEquals(self::STATE2, $target);

        $target = $stateMachine->fire(self::STATE2, self::INTERNAL_EVENT, $context);
        $this->assertEquals(self::STATE2, $target);

        $target = $stateMachine->fire(self::STATE2, self::EVENT2, $context);
        $this->assertEquals(self::STATE1, $target);

        $target = $stateMachine->fire(self::STATE1, self::EVENT3, $context);
        $this->assertEquals(self::STATE3, $target);
    }

    private function buildStateMachine(string $machineId): StateMachineInterface
    {
        $builder = StateMachineBuilderFactory::create();
        $builder->externalTransition()
            ->from(self::STATE1)
            ->to(self::STATE2)
            ->on(self::EVENT1)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->internalTransition()
            ->within(self::STATE2)
            ->on(self::INTERNAL_EVENT)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->externalTransition()
            ->from(self::STATE2)
            ->to(self::STATE1)
            ->on(self::EVENT2)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->externalTransition()
            ->from(self::STATE1)
            ->to(self::STATE3)
            ->on(self::EVENT3)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->externalTransitions()
            ->fromAmong(self::STATE1, self::STATE2, self::STATE3)
            ->to(self::STATE4)
            ->on(self::EVENT4)
            ->when($this->checkCondition())
            ->perform($this->doAction());

        $builder->build($machineId);

        $stateMachine = $builder->getFactory()->get($machineId);
        $stateMachine->showStateMachine();

        return $stateMachine;
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
