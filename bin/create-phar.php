<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

if (!isset($argv[1])) {
    echo 'No output filename supplied', PHP_EOL;
    exit(1);
}

$pharFile = $argv[1];

if (is_file($pharFile)) {
    unlink($pharFile);
}

$baseDir = dirname(__DIR__);

$finder = new Finder();
$finder->files()->in([
    $baseDir . '/config',
    $baseDir . '/src',
    $baseDir . '/resources',
    $baseDir . '/vendor'
]);

$phar = new Phar($pharFile);
$phar->setStub("#!/usr/bin/env php\n<?php Phar::mapPhar('headsec.phar'); require 'phar://headsec.phar/resources/run.php'; __HALT_COMPILER();");
$phar->compress(Phar::GZ);
$phar->buildFromIterator($finder->getIterator(), $baseDir);

echo "$pharFile successfully created", PHP_EOL;
