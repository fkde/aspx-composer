<?php

namespace Aspx\Actions;

/**
 * Check for .env file and create it if it doesn't exist
 */
class DotEnvCreateAction extends Action
{
    public function shouldRun(): bool
    {
        return $this->fs->notExists($this->appRoot . '/.env');
    }

    public function run(): void
    {
        $this->io->writeln('No .env file found...');
        $projectName = $this->io->ask('Please tell me your project name:');
        $this->fs->write($this->appRoot . '/.env', PHP_EOL . 'PROJECT_NAME=' . $projectName, FILE_APPEND);
    }

    public function noRun(): void
    {
        $this->io->writeln('.env file found.');
    }
}