<?php

namespace Aspx\Actions;

class StartContainerAction extends Action
{

    public function shouldRun(): bool
    {
        $result = $this->io->exec('docker ps | grep 443');
        return empty($result);
    }

    public function run(): void
    {
        $this->io->writeln('Docker resources installed successfully, try to start container...');
        $this->io->writeln(PHP_EOL);
        $this->io->exec('cd ' . $this->appRoot . ' && make first-install');
    }

    public function noRun(): void
    {
        $this->io->writeln('Docker resources installed successfully, but there is already a container on port 443 running.');
        $result = $this->io->ask('Should I stop it? (Y/n) ');

        if (empty($result) || in_array(strtolower($result), ['y', 'yes', 'do it', 'just do it'])) {
            $containerId = $this->io->exec('docker ps | grep 443 | cut -d \' \' -f 1');
            $this->io->exec('docker stop ' . $containerId);

            $this->reRun(5);
        } else {
            $this->io->writeln('Container creation aborted.');
            return;
        }

        $this->io->writeln('Container successfully started.');
    }

}