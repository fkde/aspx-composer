<?php

namespace Aspx;

class ActionManager
{

    use Factory;

    /** @var ActionInterface[] */
    private array $actions;

    private Config $config;

    /**
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * @param Config $config
     *
     * @return void
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    /**
     * @param ActionInterface $action
     *
     * @return void
     */
    public function add(ActionInterface $action): void
    {
        $this->actions[] = $action;
    }

    /**
     * @return ActionInterface[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return void
     */
    public function processActions(): void
    {
        $actions = $this->getActions();

        foreach ($actions as $action) {

            $action->setConfig($this->config);

            if (false === $action->shouldRun()) {

                // Do something in case the action shouldn't run
                $action->noRun();

                // As the check below wouldn't be reached we need to check it here again
                if ($action->preventFurtherExecution()) {
                    break;
                }

                continue;

            }

            try {

                // Run action
                $action->run();

            } catch (\Exception $t) {
                $this->config->get('io')->writeln($t->getMessage());
                break;
            }

            // Prevent upcoming actions from running
            if ($action->preventFurtherExecution()) {
                break;
            }

        }

    }

}