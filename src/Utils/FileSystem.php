<?php

namespace Aspx\Utils;

use Aspx\Factory;
use Aspx\Exception\PermissionException;
use Aspx\Exception\FileNotFoundException;
use Aspx\Exception\DirectoryCreateException;
use Aspx\Exception\DirectoryNotFoundException;

class FileSystem
{

    use Factory;

    /**
     * @param string $src
     * @param string $dst
     *
     * @return void
     * @throws PermissionException
     * @throws DirectoryNotFoundException
     * @throws DirectoryCreateException
     */
    public function copyFile(string $src, string $dst): void
    {
        if ($this->isDir($src)) {
            $this->copyFolder($src, $dst);
            return;
        }

        $parentDir = dirname($dst);

        if (!$this->isWritable($parentDir)) {
            throw new PermissionException('Permission denied to copy file "' . $src . '" to "' . $dst . '"');
        }

        $this->copy($src, $dst);
    }

    /**
     * @param string $src
     * @param string $dst
     *
     * @return void
     * @throws PermissionException
     * @throws DirectoryCreateException
     * @throws DirectoryNotFoundException
     */
    public function copyFolder(string $src, string $dst): void
    {
        if (!$this->isDir($dst)) {
            $this->mkDir($dst);
        }

        $files = $this->scanDir($src);

        foreach ($files as $file) {
            $sourcePath = $src . DIRECTORY_SEPARATOR . $file;
            $destinationPath = $dst . DIRECTORY_SEPARATOR . $file;
            $this->copyFile($sourcePath, $destinationPath);
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->fileExists($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function notExists(string $path): bool
    {
        return !$this->exists($path);
    }

    /**
     * @param string   $file
     * @param string   $content
     * @param int|null $flag
     *
     * @return string
     * @throws FileNotFoundException
     */
    public function write(string $file, string $content, ?int $flag = null): string
    {
        return $this->filePutContents($file, $content, $flag);
    }

    /**
     * @param string $file
     *
     * @return string|array
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     */
    public function read(string $file): string|array
    {
        if (is_dir($file)) {
            return $this->scanDir($file);
        }

        return $this->fileGetContents($file);
    }

    /* --- Begin of internal wrapper functions ---------------------------------------------------------------------- */

    /**
     * @param string $src
     * @param string $dst
     *
     * @return bool
     */
    protected function copy(string $src, string $dst): bool
    {
        return copy($src, $dst);
    }

    /**
     * @param string    $path
     * @param int|null  $permissions
     * @param bool|null $recursive
     *
     * @return bool
     * @throws PermissionException
     * @throws DirectoryCreateException
     */
    protected function mkDir(string $path, ?int $permissions = 0755, ?bool $recursive = true): bool
    {
        $parentDir = dirname($path);

        if (!$this->isWritable($parentDir)) {
            throw new PermissionException(
                sprintf('No sufficient rights to create directory "%s" in "%s"', $path, $parentDir)
            );
        }

        if (!mkdir($path, $permissions, $recursive)) {
            throw new DirectoryCreateException(sprintf('Directory "%s" could not be created', $path));
        }


        return true;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isDir(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * @param string $path
     *
     * @return array
     * @throws DirectoryNotFoundException
     */
    protected function scanDir(string $path): array
    {
        if (!$this->isDir($path)) {
            throw new DirectoryNotFoundException('Directory not found: ' . $path);
        }

        return array_diff(scandir($path), ['.', '..']);
    }

    /**
     * @param string $file
     *
     * @return string
     * @throws FileNotFoundException
     */
    protected function fileGetContents(string $file): string
    {
        $result = file_get_contents($file);

        if (false === $result) {
            throw new FileNotFoundException('File not found: ' . $file);
        }

        return $result;
    }

    /**
     * @param string   $file
     * @param string   $content
     * @param int|null $flag
     *
     * @return false|int
     * @throws FileNotFoundException
     */
    protected function filePutContents(string $file, string $content, ?int $flag = FILE_APPEND): false|int
    {
        $result = file_put_contents($file, $content, $flag);

        if (false === $result) {
            throw new FileNotFoundException('File not found: ' . $file);
        }

        return $result;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    protected function fileExists(string $file): bool
    {
        return file_exists($file);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isWritable(string $path): bool
    {
        return is_writable($path);
    }

}