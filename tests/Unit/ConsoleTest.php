<?php

namespace Aspx\Tests\Unit;

use Aspx\Utils\Console;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{

    public function test_will_output_without_newline()
    {
        $console = new Console();
        $console->write('test');

        $this->expectOutputString('test');
    }

    public function test_will_output_with_newline()
    {
        $console = new Console();
        $console->writeln('test');

        $this->expectOutputString(PHP_EOL . 'test');
    }

}