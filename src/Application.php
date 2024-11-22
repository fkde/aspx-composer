<?php

namespace Aspx;

use Aspx\Utils\Console;
use Aspx\Utils\FileSystem;

class Application
{

    private string $buildRoot;

    private string $appRoot;

    private Console $console;

    private FileSystem $fs;

    public function __construct()
    {
        $this->buildRoot = realpath(__DIR__ . '/../build');
        $this->appRoot   = realpath(dirname('.'));

        $this->console = new Console();
        $this->fs = FileSystem::factory();

        $this->install();
    }

    public function install(): void
    {

        // Check for aspx.lock and prevent further execution
        if ($this->fs->exists($this->appRoot . '/aspx.lock')) {
            $this->console->writeln('Lock file found. If you think this is an error, delete this file and try again.');
            return;
        }

        // Check for .env file and create it if it doesn't exist
        if ($this->fs->notExists($this->appRoot . '/.env')) {
            $this->console->writeln('No .env file found, creating...');
            $projectName = $this->console->ask('Please tell me your project name:');
            file_put_contents($this->appRoot . '/.env', PHP_EOL . 'PROJECT_NAME=' . $projectName, FILE_APPEND);
        }

        // In case there is a .env file, check for the existence of the env variable
        if (! $this->hasEnvVar()) {
            $try = 0;
            $this->console->writeln('.env file detected. Please add the variable PROJECT_NAME=<your-project-name> to it while I\'m waiting...');
            do {
                if (! $this->hasEnvVar()) $this->console->write('.');
                sleep(3);
                $try++;
            } while (! $this->hasEnvVar() && $try < 20);
        }

        // Copy the Makefile into the application root
        if ($this->fs->notExists($this->appRoot . '/Makefile')) {
            $this->console->writeln('Copying Makefile...');
            $this->fs->copy($this->buildRoot . '/Makefile', $this->appRoot . '/Makefile');
        }

        // Copy the docker-compose.yml into the application root
        if (! file_exists($this->appRoot . '/docker-compose.yml')) {
            $this->console->writeln('Copying docker-compose.yml...');
            $this->fs->copy($this->buildRoot . '/docker-compose.yml', $this->appRoot . '/docker-compose.yml');
        }

        // Copy the whole docker folder into the application root
        if (! is_dir($this->appRoot . '/docker')) {
            $this->console->writeln('Creating docker folder...');
            $this->fs->copyFolder($this->buildRoot . '/docker', $this->appRoot . '/docker');
        }

        $this->console->writeln('Docker resources installed successfully, try to start container...');

        // Build container through Makefile command
        exec('cd ' . $this->appRoot . ' && make first-install');

        $this->console->writeln('Done.');

        // Create lock file to prevent running the installer again
        touch($this->appRoot . '/aspx.lock');
    }

    /**
     * @return bool
     */
    private function hasEnvVar(): bool
    {
        return str_contains(file_get_contents($this->appRoot . '/.env'), 'PROJECT_NAME=');
    }

}