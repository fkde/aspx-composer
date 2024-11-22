<?php

require_once './vendor/autoload.php';

$config = [
    'fileSystem' => \Aspx\Utils\FileSystem::factory(),
    'console' => \Aspx\Utils\Console::factory(),
    'buildRoot' => realpath(__DIR__ . '/../build'),
    'appRoot' => realpath(dirname('.')),
];

(new \Aspx\Application($config))->install();