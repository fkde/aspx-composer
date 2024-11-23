<?php

namespace Aspx\Actions;

/**
 * Check for aspx.lock and prevent further execution
 */
class LockFileCheckAction extends Action
{
    /**
     * @return bool
     */
    public function shouldRun(): bool
    {
        return $this->fs->notExists($this->appRoot . '/aspx.lock');
    }

    public function noRun(): void
    {
        $this->io->writeln('Lock file found!');
        $this->io->writeln('If you think this is an error, delete this file and try again.');
    }

    /**
     * @return void
     */
    public function run(): void
    {
        // Nothing to do
    }

    /**
     * @return bool
     */
    public function preventFurtherExecution(): bool
    {
        return !$this->shouldRun();
    }

}