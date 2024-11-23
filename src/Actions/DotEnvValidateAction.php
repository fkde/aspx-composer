<?php

namespace Aspx\Actions;

class DotEnvValidateAction extends Action
{

    public function shouldRun(): bool
    {
        return false === str_contains($this->fs->read($this->appRoot . '/.env'), 'PROJECT_NAME=');
    }

    public function run(): void
    {
        $try = 0;
        $this->io->writeln('.env file detected. Please add the variable PROJECT_NAME=<your-project-name> to it while I\'m waiting...');
        do {
            if ($this->shouldRun()) $this->io->write('.');
            sleep(3);
            $try++;
        } while ($this->shouldRun() && $try < 20);
    }

}