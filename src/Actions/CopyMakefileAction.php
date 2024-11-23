<?php

namespace Aspx\Actions;

class CopyMakefileAction extends Action
{

    public function shouldRun(): bool
    {
        return $this->fs->notExists($this->appRoot . '/Makefile');
    }

    public function run(): void
    {
        $this->io->writeln('Copying Makefile...');
        $this->fs->copyFile($this->buildRoot . '/Makefile', $this->appRoot . '/Makefile');
    }

}