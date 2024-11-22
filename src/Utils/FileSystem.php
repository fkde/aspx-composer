<?php

namespace Aspx\Utils;

use Aspx\Factory;

class FileSystem
{

    use Factory;

    /**
     * @param string $src
     * @param string $dst
     *
     * @return void
     */
    public function copy(string $src, string $dst): void
    {
        if (is_dir($src)) {
            $this->copyFolder($src, $dst);
            return;
        }

        copy($src, $dst);
    }

    /**
     * @param string $src
     * @param string $dst
     *
     * @return void
     */
    public function copyFolder(string $src, string $dst): void
    {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        $files = array_diff(scandir($src), ['.', '..']);

        foreach ($files as $file) {
            $sourcePath = $src . DIRECTORY_SEPARATOR . $file;
            $destinationPath = $dst . DIRECTORY_SEPARATOR . $file;
            $this->copy($sourcePath, $destinationPath);
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
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

}