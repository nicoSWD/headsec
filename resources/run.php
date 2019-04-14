<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use nicoSWD\SecHeaderCheck\Application\Command\SecHeadersCheckCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../config'));
$loader->load('services.yml');
$container->compile();

/** @var SecHeadersCheckCommand $command */
$command = $container->get(SecHeadersCheckCommand::class);

$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName(), true);
$application->run();
