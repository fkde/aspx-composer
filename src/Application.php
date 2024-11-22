<?php

namespace Aspx;

use Aspx\Utils\Console;
use Aspx\Utils\FileSystem;

class Application
{

    private string $buildRoot;

    private string $appRoot;

    private Console $io;

    private FileSystem $fs;

    public function __construct(array $config)
    {
        $this->buildRoot = $config['buildRoot'] ?? realpath(__DIR__ . '/../build');
        $this->appRoot   = $config['appRoot'];

        $this->fs = $config['fileSystem'];
        $this->io = $config['console'];
    }

    public function install(): void
    {

        // Check for aspx.lock and prevent further execution
        if ($this->fs->exists($this->appRoot . '/aspx.lock')) {
            $this->io->writeln('Lock file found. If you think this is an error, delete this file and try again.');
            return;
        }

        // Check for .env file and create it if it doesn't exist
        if ($this->fs->notExists($this->appRoot . '/.env')) {
            $this->io->writeln('No .env file found, creating...');
            $projectName = $this->io->ask('Please tell me your project name:');
            $this->fs->write($this->appRoot . '/.env', PHP_EOL . 'PROJECT_NAME=' . $projectName, FILE_APPEND);
        }

        // In case there is a .env file, check and wait for PROJECT_NAME variable
        if (! $this->hasEnvVar()) {
            $try = 0;
            $this->io->writeln('.env file detected. Please add the variable PROJECT_NAME=<your-project-name> to it while I\'m waiting...');
            do {
                if (! $this->hasEnvVar()) $this->io->write('.');
                sleep(3);
                $try++;
            } while (! $this->hasEnvVar() && $try < 20);
        }

        // Copy the Makefile into the application root
        if ($this->fs->notExists($this->appRoot . '/Makefile')) {
            $this->io->writeln('Copying Makefile...');
            $this->fs->copyFile($this->buildRoot . '/Makefile', $this->appRoot . '/Makefile');
        }

        // Copy the docker-compose.yml into the application root
        if (! file_exists($this->appRoot . '/docker-compose.yml')) {
            $this->io->writeln('Copying docker-compose.yml...');
            $this->fs->copyFile($this->buildRoot . '/docker-compose.yml', $this->appRoot . '/docker-compose.yml');
        }

        // Copy the whole docker folder into the application root
        if (! is_dir($this->appRoot . '/docker')) {
            $this->io->writeln('Creating docker folder...');
            $this->fs->copyFolder($this->buildRoot . '/docker', $this->appRoot . '/docker');
        }

        $this->io->writeln('Docker resources installed successfully, try to start container...');

        // Build container through Makefile command
        $this->io->exec('cd ' . $this->appRoot . ' && make first-install');

        $this->io->writeln('Done.');

        // Create lock file to prevent running the installer again
        touch($this->appRoot . '/aspx.lock');
    }

    /**
     * @return bool
     */
    private function hasEnvVar(): bool
    {
        return str_contains($this->fs->read($this->appRoot . '/.env'), 'PROJECT_NAME=');
    }

}