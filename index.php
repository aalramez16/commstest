<?php
declare(strict_types=1);
/**
 * index.php
 * --------------------------------------------------------------------------
 * This is where each request lands. It initializes the app and
 * sets up the router to handle incoming requests.
 * 
 * Most changes won't take place here unless they're architectural in nature.
 * 
 */

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use CommsTest\Middleware\AuthMiddleware;
use Doctrine\ORM\EntityManager;

/* ==========================================================================
 * 
 *      Initial Configurations
 * 
 * ========================================================================== */
// Autoloader intelligently grabs dependencies when used.
require_once __DIR__ . '/vendor/autoload.php';

// Attach environment variables (config)
$config = require __DIR__ . '/.config.php';

// Normalize time zone
date_default_timezone_set($config['TIME_ZONE']);

/* ==========================================================================
 * 
 *      Dependency Injection Containers
 * 
 * ========================================================================== */
// DI Containers
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(['app.config' => $config]);
$containerBuilder->addDefinitions(__DIR__ . '/src/App/container.php'); // The actual definitions are here
$container = $containerBuilder->build();

// Set the container so the app factory can inject any needed dependencies for route resolution
AppFactory::setContainer($container);
$app = AppFactory::create();

/* ==========================================================================
 * 
 *      Add Routes
 * 
 * ========================================================================== */
/**
 * core.php returns a callable that registers routes.
 * 
 * The callable gives a response for the root endpoint and also imports other endpoints.
 * If an additional endpoint group gets added, it should be imported to core.php, not here.
 */
(require $config['ROOT_DIR'] . '/src/App/Routes/core.php')($app);

/* ==========================================================================
 * 
 *      Add Middleware
 * 
 * ========================================================================== */
// Slim Middleware Built-ins 
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware((bool)$config['IS_DEVELOPMENT'], true, true);

// Custom Middleware
$app->add(new AuthMiddleware($container->get(EntityManager::class)));

/* ==========================================================================
 * 
 *      Run the App
 * 
 * ========================================================================== */
// Resolve the current route
$app->run();