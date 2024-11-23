<?php

namespace Aspx\Tests\Integration;

use Aspx\Config;
use Aspx\Application;
use Prophecy\Argument;
use Aspx\ActionManager;
use Aspx\Utils\Console;
use Aspx\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ApplicationTest extends TestCase
{

    use ProphecyTrait;

    private string $testDir;

    protected function setUp(): void
    {
        $this->testDir = sys_get_temp_dir() . '/phpunit_test_' . uniqid();
        mkdir($this->testDir, 0755, true);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . $this->testDir);
    }

    public function test_application_can_be_installed()
    {
        $console = $this->prophesize(Console::class);
        $console->ask(Argument::any())->shouldBeCalled()->willReturn('TestProject');
        $console->writeln(Argument::any())->shouldBeCalled();
        $console->exec(Argument::any())->shouldBeCalled();

        $app = new Application(new Config([
            'buildRoot' => realpath(__DIR__ . '/../../build'),
            'appRoot' => $this->testDir,
            'am' => ActionManager::factory(),
            'fs' => FileSystem::factory(),
            'io' => $console->reveal(),
        ]));

        $app->install();

        $this->assertFileExists($this->testDir . '/.env');
        $this->assertFileExists($this->testDir . '/Makefile');
        $this->assertFileExists($this->testDir . '/aspx.lock');
        $this->assertFileExists($this->testDir . '/docker-compose.yml');
        $this->assertDirectoryExists($this->testDir . '/docker');

        $this->assertStringEqualsFile($this->testDir . '/.env', PHP_EOL . 'PROJECT_NAME=TestProject');
    }

}