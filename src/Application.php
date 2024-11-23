<?php

namespace Aspx;

use Aspx\Utils\FileSystem;
use Aspx\Actions\CopyMakefileAction;
use Aspx\Actions\DotEnvCreateAction;
use Aspx\Actions\LockFileCheckAction;
use Aspx\Actions\CreateLockFileAction;
use Aspx\Actions\DotEnvValidateAction;
use Aspx\Actions\StartContainerAction;
use Aspx\Actions\ShowSplashScreenAction;
use Aspx\Actions\CopyDockerFolderAction;
use Aspx\Actions\CopyDockerComposeAction;
use Aspx\Exception\DirectoryNotFoundException;

class Application
{

    private ActionManager $am;

    private FileSystem $fs;

    /**
     * @param Config $config
     *
     * @throws DirectoryNotFoundException
     */
    public function __construct(Config $config)
    {
        $this->am = $config->get('am');
        $this->am->setConfig($config);

        $this->fs = $config->get('fs');

        if ($this->fs->notExists($config->get('buildRoot'))
            || $this->fs->notExists($config->get('appRoot'))
        ) {
            throw new DirectoryNotFoundException('Check if both the `buildRoot` and the `appRoot` exist and ensure they\'re readable.');
        }
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->am->add(new ShowSplashScreenAction());
        $this->am->add(new LockFileCheckAction());
        $this->am->add(new DotEnvCreateAction());
        $this->am->add(new DotEnvValidateAction());
        $this->am->add(new CopyMakefileAction());
        $this->am->add(new CopyDockerComposeAction());
        $this->am->add(new CopyDockerFolderAction());
        $this->am->add(new StartContainerAction());
        $this->am->add(new CreateLockFileAction());

        $this->am->processActions();
    }

}