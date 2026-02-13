<?php
declare(strict_types=1);

namespace CommsTest\Controller;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\Serializer\Serializer;
use CommsTest\Service\ValidatorService;

class Controller {
    protected ValidatorService $validatorService;
    
    public function __construct(ValidatorService $validatorService) {
        $this->validatorService = $validatorService;
    }

    protected function withValidationErrors(array $errors, Response $response): Response {
        $errorBody = json_encode([
            'errors' => $errors
        ]);
        $response->getBody()->write($errorBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(422);
    }

    protected function withError(int $code, \Throwable $e, Response $response): Response {
        $errorBody = json_encode([
            'error' => $e->getMessage()
        ]);
        $response->getBody()->write($errorBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }

    protected function withJSON(int $code, string $payloadData, Response $response): Response {
        $response->getBody()->write($payloadData);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }
}