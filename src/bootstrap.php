<?php

require_once './vendor/autoload.php';

$config = new \Aspx\Config([
    'appRoot'   => realpath(dirname('.')),
    'buildRoot' => realpath(__DIR__ . '/../build'),
    'am'        => \Aspx\ActionManager::factory(),
    'fs'        => \Aspx\Utils\FileSystem::factory(),
    'io'        => \Aspx\Utils\Console::factory(),
]);

(new \Aspx\Application($config))->install();

print PHP_EOL . PHP_EOL;