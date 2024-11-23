<?php

namespace Aspx\Actions;

use Aspx\ActionInterface;
use Aspx\Config;
use Aspx\Utils\Console;
use Aspx\Utils\FileSystem;

abstract class Action implements ActionInterface
{

    protected string $buildRoot;

    protected string $appRoot;

    protected Console $io;

    protected FileSystem $fs;

    /**
     * @param Config $config
     *
     * @return void
     */
    public function setConfig(Config $config): void
    {
        $this->buildRoot = $config->get('buildRoot');
        $this->appRoot   = $config->get('appRoot');

        $this->fs = $config->get('fs');
        $this->io = $config->get('io');
    }

    /**
     * @return bool
     */
    public function shouldRun(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function preventFurtherExecution(): bool
    {
        return false;
    }

    /**
     * @return void
     */
    public function noRun(): void
    {
        // Nothing to do
    }

    /**
     * @param int $delay In seconds
     *
     * @return void
     */
    public function reRun(int $delay = 0): void
    {
        $this->io->writeln('Rerun action');

        if ($delay) $this->io->write(' in 5 seconds...'); sleep($delay);

        if (! $this->shouldRun()) {
            $this->noRun(); return;
        }

        if ($delay === 0) $this->io->write(' now.');

        $this->run();
    }
}