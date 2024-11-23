<?php

namespace Aspx\Actions;

class CreateLockFileAction extends Action
{

    public function run(): void
    {
        touch($this->appRoot . '/aspx.lock');
    }

}