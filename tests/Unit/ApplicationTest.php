<?php

namespace Aspx\Tests\Unit;

use Aspx\Application;
use Aspx\Utils\Console;
use Aspx\Utils\FileSystem;
use PhpParser\Node\Arg;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class ApplicationTest extends TestCase
{

    use ProphecyTrait;

    public function test_application_can_be_installed()
    {
        $console = $this->prophesize(Console::class);
        $console->ask(Argument::any())->shouldBeCalled()->willReturn('TestProject');
        $console->writeln(Argument::any())->shouldBeCalled();
        $console->exec(Argument::any(), Argument::type('null'), Argument::type('null'))->shouldBeCalled();

        $fileSystem = FileSystem::factory();

        $testDir = sys_get_temp_dir() . '/phpunit_test_install_' . uniqid();
        mkdir($testDir, 0755, true);

        $app = new Application([
            'fileSystem' => $fileSystem,
            'console' => $console->reveal(),
            'buildRoot' => null,
            'appRoot' => $testDir
        ]);

        $app->install();

        $this->assertFileExists($testDir . '/.env');
        $this->assertFileExists($testDir . '/Makefile');
        $this->assertFileExists($testDir . '/aspx.lock');
        $this->assertFileExists($testDir . '/docker-compose.yml');
        $this->assertDirectoryExists($testDir . '/docker');

        $this->assertStringEqualsFile($testDir . '/.env', PHP_EOL . 'PROJECT_NAME=TestProject');

        exec('rm -rf ' . $testDir);
    }

}