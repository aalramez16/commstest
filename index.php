<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;

require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload
// Normalize time zone

$config = require __DIR__ . '/.config.php';
date_default_timezone_set($config['TIME_ZONE']);

// DI Containers
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(['app.config' => $config]);
$containerBuilder->addDefinitions(__DIR__ . '/src/App/container.php'); // The actual definitions are here
$container = $containerBuilder->build();

// Set the container so the app factory can inject any needed dependencies for route resolution
AppFactory::setContainer($container);
$app = AppFactory::create();

// Include routes
(require __DIR__ . '/src/App/routes.php')($app);
(require __DIR__ . '/src/App/middleware.php')($app);

// Resolve the current route
$app->run();