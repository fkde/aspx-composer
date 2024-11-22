<?php

namespace Aspx\Utils;

class Console
{

    /**
     * Outputs a message to the console.
     *
     * @param string $message
     *
     * @return void
     */
    public function write(string $message): void
    {
        print $message;
    }

    /**
     * Outputs a message to the console with a newline.
     *
     * @param string $message
     *
     * @return void
     */
    public function writeln(string $message): void
    {
        echo PHP_EOL . $message;
    }

    /**
     * Prompts the user with a question and waits for input.
     *
     * @param string $question
     *
     * @return string
     */
    public function ask(string $question): string
    {
        $this->writeln($question);
        return trim(fgets(STDIN));
    }

}