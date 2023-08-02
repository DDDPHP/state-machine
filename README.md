State Machine for PHP
========
The State Machine library is aimed to provide a **easy use**, **lightweight**, **flexible** and **extensible**, and **type safe** PHP state machine implementation for enterprise usage.

Installation (via composer)
---------------

```js
{
    "require": {
        "dddphp/state-machine": "*"
    }
}
```
User Guide
----------------
### Get Starting

* **State Machine Builder**

    - State machine builder is used to generate state machine definition. StateMachineBuilder can be created by StateMachineBuilderFactory.
    - The StateMachineBuilder is composed of TransitionBuilder (InternalTransitionBuilder / ExternalTransitionBuilder) which is used to build transition between states.
    - The internal state is implicitly built during transition creation or state action creation.
    - All the state machine instances created by the same state machine builder share the same definition data for memory usage optimize.
    - State machine builder generate state machine definition in a lazy manner. When builder create first state machine instance, the state machine definition will be generated which is time consumed. But after state machine definition generated, the following state machine instance creation will be much faster. Generally, state machine builder should be reused as much as possible.

    In order to create a state machine, user need to create state machine builder first. For example:

    ```php
    $builder = StateMachineBuilderFactory::create();
    ```

* **Fluent API**
After state machine builder was created, we can use fluent API to define state/transition/action of the state machine.

    ```php
    $builder->externalTransition()
        ->from(self::STATEA)
        ->to(self::STATEB)
        ->on(self::EVENTGoToB)
        ->when($this->checkCondition())
        ->perform($this->doAction());
    ```

    An **external transition** is built between state 'A' to state 'B' and triggered on received event 'GoToB'.

    ```php
    $builder->internalTransition()
        ->within(self::STATEA)
        ->on(self::INTERNAL_EVENT)
        ->when($this->checkCondition())
        ->perform($this->doAction());
    ```

    An **internal transition** with priority set to high is build inside state 'A' on event 'INTERNAL_EVENT' perform '$this->doAction()'. The internal transition means after transition complete, no state is exited or entered. The transition priority is used to override original transition when state machine extended.

    ```php
    $builder->externalTransition()
            ->from(self::STATEC)
            ->to(self::STATED)
            ->on(self::EVENTGoToD)
            ->when(
                new class () implements ConditionInterface {
                    public function isSatisfied($context): bool
                    {
                        echo "Check condition : " . $context . "\n";
                        return true;
                    }
                    public function name(): string
                    {
                        return '';
                    }
                })
            ->perform(
                new class () implements ActionInterface {
                    public function execute($from, $to, $event, $context): void
                    {
                        echo $context . " from:" . $from . " to:" . $to . " on:" . $event;
                    }
                };
            );
    ```

    An **conditional transition** is built from state 'C' to state 'D' on event 'GoToD' when external context satisfied the condition restriction, then call action method.

*   **New State Machine Instance**

    After user defined state machine behaviour, user could create a new state machine instance through builder. Note, once the state machine instance is created from the builder, the builder cannot be used to define any new element of state machine anymore.

    New state machine from state machine builder.

    ```php
    $stateMachine = $builder->build(self::MACHINE_ID);
    ```

*   **Trigger Transitions**

    After state machine was created, user can fire events along with context to trigger transition inside state machine. e.g.

    ```php
    $target = $stateMachine->fire(self::STATE1, self::EVENT1, $this->context);
    ```
