<?php
declare(strict_types=1);

namespace CommsTest\Middleware;

use CommsTest\Entity\User;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware {
    private EntityManager $entityManager;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $userRepository = $this->entityManager->getRepository(User::class);
        $token = $request->getHeaderLine('X-Auth-Token');

        if (!$token) {
            $response = new Response(401);
            $response->getBody()->write(json_encode(['error' => 'Token required.']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user = $userRepository->findOneBy(['token' => $token]);

        if (!$user) {
            $response = new Response(403);
            $response->getBody()->write(json_encode(['error' => 'Invalid token.']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $request = $request->withAttribute('user', $user);
        return $handler->handle($request);
    }
}