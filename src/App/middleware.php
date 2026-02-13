<?php
declare(strict_types=1);

use Slim\App;
use CommsTest\Middleware\AuthMiddleware;
use Doctrine\ORM\EntityManager;

return function (App $app) use ($container, $config) {
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware((bool)$config['IS_DEVELOPMENT'], true, true);
    $app->add(new AuthMiddleware(
        $container->get(EntityManager::class),
    ));
};
