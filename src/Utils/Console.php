<?php

namespace Aspx\Utils;

use Aspx\Factory;

class Console
{

    use Factory;

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
     * @param string     $command
     * @param array|null $output
     * @param int|null   $status
     *
     * @return string
     */
    public function exec(string $command, ?array $output = null, ?int $status = null): string
    {
        return exec($command, $output, $status);
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