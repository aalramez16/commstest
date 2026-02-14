<?php
declare(strict_types=1);

namespace CommsTest\Controller;

use CommsTest\DTO\Message\MessageDTOMapper;
use CommsTest\DTO\MessageResponseDTO;
use CommsTest\Entity\Message;
use CommsTest\Service\DataTransferService;
use CommsTest\Service\DTOKey;
use CommsTest\Service\EntityService\MessageService;
use CommsTest\Service\ValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Psr7\ServerRequest as Request;

/**
 * The `MessageController` is responsible for orchestrating responses to API endpoints concerned with messages.
 */
class MessageController extends Controller {
    private MessageService $messageService;
    private DataTransferService $dataTransferService;

    public function __construct(
        MessageService $messageService,
        ValidatorService $validatorService,
        DataTransferService $dataTransferService,
    ) {
        parent::__construct($validatorService);
        $this->messageService = $messageService;
        $this->dataTransferService = $dataTransferService;
    }

    /**
     * Retrieves a list of all Messages ordered by newest first.
     *
     * Delegates message retrieval to `MessageService`. Returns a JSON response
     * with status `200` containing the message list on success.
     * If an unexpected error occurs, returns a `500` response with the error message.
     *
     * @return Response JSON response containing messages or error information.
     */
    public function list(Request $request, Response $response): Response {
        try {
            $messages = $this->messageService->listMessages();
        } catch (\Throwable $e) {
            return $this->withError(500, $e, $response);
        }
        $readMessageDTOs = MessageDTOMapper::toReadDTOsRoomAgnostic($messages);
        $responseData = $this->dataTransferService->serialize($readMessageDTOs);
        return $this->withJSON(200, $responseData, $response);
    }

    /**
     * Creates a new Message from an authenticated request.
     *
     * The authenticated `User` is derived from the request attribute populated by
     * {@see \CommsTest\Middleware\AuthMiddleware}. The request body is mapped into a
     * `MessageCreateRequestDTO` and validated before delegating creation to
     * `MessageService`.
     *
     * On success, returns a `201` JSON response containing a `MessageResponseDTO`.
     * On validation failure, returns a `422` response with error details.
     */
    public function create(Request $request, Response $response): Response {
        
        // Parse body to PHP associative array
        $payload = (array) $request->getParsedBody();

        // Get User entity (assigned in auth middleware)
        $user = $request->getAttribute('user');

        // Prepare data transfer object.
        $createMessageDTO = $this->dataTransferService->buildDTOFor(Message::class, 'create', $payload);
        $createMessageDTO->contents = $payload['contents'] ?? null;
        $createMessageDTO->roomId = $payload['roomId'] ?? null;

        // Validation stage
        $errors = $this->validatorService->validate($createMessageDTO);
        if ($errors) {
            return $this->withValidationErrors($errors, $response);
        }

        try {
            // MessageService sends message for DB
            $message = $this->messageService->createMessage($user, $createMessageDTO);
        } catch (\Throwable $e) {
            return $this->withError(404, $e, $response);
        }

        // Prepare data for API display
        $readMessageDTO = MessageDTOMapper::toReadDTO($message);
        $responseData = $this->dataTransferService->serialize($readMessageDTO);
        return $this->withJSON(201, $responseData, $response);
    }

    public function getById(Request $request, Response $response, array $args): Response {
        $id = (int) $args['id'];
        try {
            $message = $this->messageService->getMessageById($id);
        } catch (\Throwable $e) {
            return $this->withError(404, $e, $response);
        }
        $readMessageDTO = MessageDTOMapper::toReadDTO($message);
        $responseData = $this->dataTransferService->serialize($readMessageDTO);
        return $this->withJSON(200, $responseData, $response);
    }
}