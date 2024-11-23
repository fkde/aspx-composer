<?php

namespace Aspx\Tests\Integration;

use Aspx\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Aspx\Exception\PermissionException;
use Aspx\Exception\DirectoryNotFoundException;

class FileSystemTest extends TestCase
{

    private string $testDir;
    private string $fixtureFile;
    private string $fixtureFolder;
    private string $fixtureFolderWithContents;

    private const FIXTURE_FILE_NAME = 'testFile.txt';
    private const FIXTURE_FOLDER_NAME = 'testFolder';
    private const FIXTURE_FOLDER_WITH_CONTENTS_NAME = 'testFolderWithContents';

    protected function setUp(): void
    {
        $fixturesFolder = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Fixture' . DIRECTORY_SEPARATOR;
        $this->fixtureFile = $fixturesFolder . self::FIXTURE_FILE_NAME;
        $this->fixtureFolder = $fixturesFolder . self::FIXTURE_FOLDER_NAME;
        $this->fixtureFolderWithContents = $fixturesFolder . self::FIXTURE_FOLDER_WITH_CONTENTS_NAME;

        $this->testDir = sys_get_temp_dir() . '/phpunit_test_' . uniqid();
        mkdir($this->testDir, 0755, true);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . $this->testDir);
    }

    public function test_file_can_be_copied()
    {
        $fs = new FileSystem();
        $targetPath = $this->testDir . '/' . static::FIXTURE_FILE_NAME;
        $fs->copyFile($this->fixtureFile, $targetPath);

        $this->assertFileExists($targetPath);
        $this->assertFileIsReadable($targetPath);
    }

    public function test_folder_can_be_copied()
    {
        $fs = new FileSystem();
        $targetPath = $this->testDir . '/' . static::FIXTURE_FOLDER_NAME;
        $fs->copyFolder($this->fixtureFolder, $targetPath);

        $this->assertDirectoryExists($targetPath);
        $this->assertDirectoryIsReadable($targetPath);
    }

    public function test_folder_with_contents_can_be_copied()
    {
        $fs = new FileSystem();
        $targetPath = $this->testDir . '/' . static::FIXTURE_FOLDER_WITH_CONTENTS_NAME;
        $fs->copyFolder($this->fixtureFolderWithContents, $targetPath);

        $this->assertDirectoryExists($targetPath);
        $this->assertDirectoryIsReadable($targetPath);
        $this->assertFileExists($targetPath . '/content.txt');
    }

    public function test_file_cannot_be_copied_due_to_permission_error()
    {
        $this->expectException(PermissionException::class);

        $fs = new FileSystem();
        $targetPath = '/root';
        $fs->copyFile($this->fixtureFile, $targetPath);
    }

    public function test_file_can_be_created()
    {
        $fs = new FileSystem();
        $fs->write($this->testDir . '/test.txt', 'test');
        $this->assertFileExists($this->testDir . '/test.txt');
        $this->assertFileIsReadable($this->testDir . '/test.txt');
    }

    public function test_file_can_be_checked()
    {
        $fs = new FileSystem();
        $fs->write($this->testDir . '/test.txt', 'test');
        $this->assertTrue($fs->exists($this->testDir . '/test.txt'));
    }

    public function test_file_can_be_negatively_checked()
    {
        $fs = new FileSystem();
        $fs->write($this->testDir . '/test.txt', 'test');
        $this->assertTrue($fs->notExists($this->testDir . '/test2.txt'));
    }

    public function test_file_can_be_read()
    {
        $fs = new FileSystem();
        $fs->write($this->testDir . '/test.txt', 'test');
        $this->assertSame($fs->read($this->testDir . '/test.txt'), 'test');
    }

    public function test_directory_can_be_read()
    {
        $fs = new FileSystem();
        $targetPath = $this->testDir . '/' . static::FIXTURE_FOLDER_WITH_CONTENTS_NAME;
        $fs->copyFolder($this->fixtureFolderWithContents, $targetPath);

        $result = $fs->read($this->testDir . '/' . static::FIXTURE_FOLDER_WITH_CONTENTS_NAME);

        $this->assertIsArray($result);
        $this->assertContains('content.txt', $result);
    }

    public function test_directory_cannot_be_copied_due_to_permission_error()
    {
        $this->expectException(PermissionException::class);

        $fs = new FileSystem();
        $fs->copyFolder($this->fixtureFolder, '/root/' . static::FIXTURE_FOLDER_NAME);
    }

    public function test_directory_cannot_be_found()
    {
        $this->expectException(DirectoryNotFoundException::class);

        $fs = new FileSystem();
        $fs->copyFolder('/will-not-exist-on-any-device', $this->testDir . '/will-not-exist-on-any-device');
    }

}