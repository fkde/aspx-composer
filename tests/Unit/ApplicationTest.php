<?php

namespace Aspx\Tests\Unit;

use Aspx\ActionManager;
use Aspx\Application;
use Aspx\Config;
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
        $console->exec(Argument::any())->shouldBeCalled();

        $testDir = sys_get_temp_dir() . '/phpunit_test_install_' . uniqid();
        mkdir($testDir, 0755, true);

        $app = new Application(new Config([
            'buildRoot' => realpath(__DIR__ . '/../../build'),
            'appRoot' => $testDir,
            'am' => ActionManager::factory(),
            'fs' => FileSystem::factory(),
            'io' => $console->reveal(),
        ]));

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