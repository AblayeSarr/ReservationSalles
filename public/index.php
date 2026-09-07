<?php

declare(strict_types=1);

session_start();

use App\Application;
use DI\ContainerBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(
    require dirname(__DIR__) . '/config/container.php'
);

$container = $builder->build();
$application = $container->get(Application::class);
$application->run();
