<?php

namespace Aspx\Actions;

class CopyDockerComposeAction extends Action
{

    public function shouldRun(): bool
    {
        return $this->fs->notExists($this->appRoot . '/docker-compose.yml');
    }

    public function run(): void
    {
        $this->io->writeln('Copying docker-compose.yml...');
        $this->fs->copyFile($this->buildRoot . '/docker-compose.yml', $this->appRoot . '/docker-compose.yml');
    }

}