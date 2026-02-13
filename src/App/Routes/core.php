<?php
declare(strict_types=1);

use CommsTest\Controller\MessageController;
use CommsTest\Controller\RoomController;
use CommsTest\Controller\UserController;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest as Request;
use Slim\App;

return function (App $app) {
    $app->get('/', function(Request $request, Response $response) {
        $response->getBody()->write('Hello World!');
        return $response;
    });
    
    $app->get('/keygen', function(Request $request, Response $response) {
        $response->getBody()->write(json_encode([
            'key' => base64_encode(random_bytes(32))
            ]));
        return $response
            ->withHeader('Content-Type', 'application/json');
    });
    
    $app->get('/messages', [MessageController::class, 'list']);

    $app->get('/message/{id}', [MessageController::class, 'getById']);
    
    $app->post('/message/create', [MessageController::class, 'create']);

    $app->get('/rooms', [RoomController::class, 'list']);

    $app->get('/room/{id}', [RoomController::class, 'getById']);

    $app->get('/room/{id}/messages', [RoomController::class, 'listMessages']);
    
    $app->post('/room/create', [RoomController::class, 'create']);

    $app->get('/users', [UserController::class, 'list']);

    $app->get('/user/{id}', [UserController::class, 'getById']);
    
    $app->post('/user/create', [UserController::class, 'create']);
};