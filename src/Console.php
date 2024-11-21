<?php

namespace Aspx;

class Console
{

    public function write(string $message)
    {
        echo $message;
    }

    public function writeln(string $message)
    {
        echo PHP_EOL . $message;
    }

    public function ask(string $question)
    {
        $this->writeln($question);
        return trim(fgets(STDIN));
    }

}