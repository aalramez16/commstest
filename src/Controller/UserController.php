<?php
declare(strict_types=1);

namespace CommsTest\Controller;

use CommsTest\DTO\User\UserDTOMapper;
use CommsTest\Entity\User;
use CommsTest\Service\DataTransferService;
use CommsTest\Service\EntityService\UserService;
use CommsTest\Service\ValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Psr7\ServerRequest as Request;

/**
 * The `UserController` orchestrates API endpoints for User management.
 *
 * Uses a "provisioner" (authenticated user) to create new User accounts.
 */
class UserController extends Controller
{
    private UserService $userService;
    private DataTransferService $dataTransferService;

    public function __construct(
        UserService $userService,
        ValidatorService $validatorService,
        DataTransferService $dataTransferService
    ) {
        parent::__construct($validatorService);
        $this->userService = $userService;
        $this->dataTransferService = $dataTransferService;
    }

    /**
     * Lists all users ordered by newest first.
     */
    public function list(Request $request, Response $response): Response {
        try {
            $users = $this->userService->listUsers();
        } catch (\Throwable $e) {
            return $this->withError(500, $e, $response);
        }

        $readUserDTOs = UserDTOMapper::toReadDTOs($users);
        $responseData = $this->dataTransferService->serialize($readUserDTOs);

        return $this->withJSON(200, $responseData, $response);
    }

    /**
     * Creates a new User using a provisioner (authenticated user).
     */
    public function create(Request $request, Response $response): Response {
        $payload = (array) $request->getParsedBody();

        // Provisioner is the authenticated user creating the new User
        $provisioner = $request->getAttribute('user');

        // Prepare DTO
        $createUserDTO = $this->dataTransferService->buildDTOFor(User::class, 'create', $payload);
        $createUserDTO->name = $payload['name'] ?? null;

        // Validate
        $errors = $this->validatorService->validate($createUserDTO);
        if ($errors) {
            return $this->withValidationErrors($errors, $response);
        }

        // Use UserService to create the User
        $user = $this->userService->createUser($provisioner, $createUserDTO);

        // Prepare DTO for response
        $readUserDTO = UserDTOMapper::toReadDTO($user);
        $responseData = $this->dataTransferService->serialize($readUserDTO);

        return $this->withJSON(201, $responseData, $response);
    }

    public function getSelf(Request $request, Response $response): Response {
        $self = $request->getAttribute('user');
        $readUserDTO = $this->dataTransferService->buildDTOFor(User::class, 'read', $self);
        $responseData = $this->dataTransferService->serialize($readUserDTO);

        return $this->withJSON(200, $responseData, $response);
    }

    public function getById(Request $request, Response $response, array $args): Response {
        if ($args['id'] === 'self') {
            return $this->getSelf($request, $response);
        }
        $id = (int) $args['id'];
        try {
            $user = $this->userService->getUserById($id);
        } catch (\Throwable $e) {
            return $this->withError(404, $e, $response);
        }
        $readUserDTO = UserDTOMapper::toReadDTO($user);
        $responseData = $this->dataTransferService->serialize($readUserDTO);
        return $this->withJSON(200, $responseData, $response);
    }
}
