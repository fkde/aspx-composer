<?php

namespace Aspx;

class Application
{

    private Console $console;

    public function __construct()
    {
        $this->console = new Console();

        $this->install();
    }

    public function install(): void
    {
        $buildRoot = realpath(__DIR__ . '/../build');
        $applicationRoot = realpath(dirname('.'));

        if (file_exists($applicationRoot . '/aspx.lock')) {
            $this->console->writeln('Lock file found. If you think this is an error, delete this file and try again.');
        }

        if (! file_exists($applicationRoot . '/.env')) {
            $this->console->writeln('No .env file found, creating...');
            $projectName = $this->console->ask('Please tell me your project name:');
            file_put_contents($applicationRoot . '/.env', PHP_EOL . 'PROJECT_NAME=' . $projectName, FILE_APPEND);
        } else {
            $try = 0;
            $this->console->writeln('.env file detected. Please add the variable PROJECT_NAME=<your-project-name> to it while I\'m waiting...');
            do {
                if (! str_contains(file_get_contents($applicationRoot . '/.env'), 'PROJECT_NAME=')) {
                    $this->console->write('.');
                }
                sleep(3);
                $try++;
            } while (! str_contains(file_get_contents($applicationRoot . '/.env'), 'PROJECT_NAME=') && $try < 10);
        }

        if (! file_exists($applicationRoot . '/Makefile')) {
            $this->console->writeln('Copying Makefile...');
            copy($buildRoot . '/Makefile', $applicationRoot . '/Makefile');
        }

        if (! file_exists($applicationRoot . '/docker-compose.yml')) {
            $this->console->writeln('Copying docker-compose.yml...');
            copy($buildRoot . '/docker-compose.yml', $applicationRoot . '/docker-compose.yml');
        }

        if (! is_dir($applicationRoot . '/docker')) {
            $this->console->writeln('Creating docker folder...');
            $this->copyFolder($buildRoot . '/docker', $applicationRoot . '/docker');
        }

        $this->console->writeln('Docker resources installed successfully, try to start container...');

        exec('cd ' . $applicationRoot . ' && make first-install');

        //touch($applicationRoot . '/aspx.lock');
    }

    private function copyFolder($from, $to): void
    {
        if (!is_dir($to)) {
            mkdir($to, 0755, true);
        }

        $files = scandir($from);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue; // Verzeichniseinträge für aktuelle und übergeordnete Verzeichnisse überspringen
            }

            $sourcePath = $from . DIRECTORY_SEPARATOR . $file;
            $destinationPath = $to . DIRECTORY_SEPARATOR . $file;

            if (is_dir($sourcePath)) {
                $this->copyFolder($sourcePath, $destinationPath);
            } else {
                copy($sourcePath, $destinationPath);
            }
        }
    }

}