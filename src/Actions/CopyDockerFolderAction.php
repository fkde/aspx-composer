<?php

namespace Aspx\Actions;

use Aspx\Exception\DirectoryNotFoundException;

class CopyDockerFolderAction extends Action
{

    public function shouldRun(): bool
    {
        return $this->fs->notExists($this->appRoot . '/docker');
    }

    public function run(): void
    {
        $this->io->writeln('Creating docker folder...');
        $this->fs->copyFolder($this->buildRoot . '/docker', $this->appRoot . '/docker');
    }

}