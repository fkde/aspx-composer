<?php

namespace Aspx\Actions;

class ShowSplashScreenAction extends Action
{

    public function run(): void
    {
        $this->io->writeln(<<<TEXT
         ------------------- -------- 
        | ASPX for Composer | v1.0.0 |
         ------------------- --------
        TEXT);
        $this->io->writeln('');
    }

}