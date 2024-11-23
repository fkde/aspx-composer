<?php

namespace Aspx;

interface ActionInterface
{
    /**
     * Determine if the action should be executed.
     *
     * @return bool
     */
    public function shouldRun(): bool;

    /**
     * Contains the action intent
     *
     * @return void
     */
    public function run(): void;

    /**
     * When `shouldRun()` returns `false` this method is being called.
     *
     * @return void
     */
    public function noRun(): void;

    /**
     * Prevent upcoming actions being executed.
     *
     * @return bool
     */
    public function preventFurtherExecution(): bool;

}