<?php
declare(strict_types=1);

namespace CommsTest\Controller;

use CommsTest\DTO\Message\MessageDTOMapper;
use CommsTest\Entity\Message;
use CommsTest\Entity\Room;
use CommsTest\Service\DataTransferService;
use CommsTest\Service\EntityService\RoomService;
use CommsTest\Service\ValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Psr7\ServerRequest as Request;

/**
 * The `RoomController` orchestrates API endpoints for Room management.
 */
class RoomController extends Controller
{
    private RoomService $roomService;
    private DataTransferService $dataTransferService;

    public function __construct(
        RoomService $roomService,
        ValidatorService $validatorService,
        DataTransferService $dataTransferService
    ) {
        parent::__construct($validatorService);
        $this->roomService = $roomService;
        $this->dataTransferService = $dataTransferService;
    }

    /**
     * Lists all rooms ordered by newest first.
     */
    public function list(Request $request, Response $response): Response
    {
        try {
            $rooms = $this->roomService->listRooms();
        } catch (\Throwable $e) {
            return $this->withError(500, $e, $response);
        }

        $readRoomDTOs = $this->dataTransferService->buildDTOArrayFor(Room::class, 'read', $rooms);
        $responseData = $this->dataTransferService->serialize($readRoomDTOs);
        return $this->withJSON(200, $responseData, $response);
    }

    /**
     * Creates a new Room.sur
     */
    public function create(Request $request, Response $response): Response
    {
        $payload = (array) $request->getParsedBody();
        $user = $request->getAttribute('user');

        $createRoomDTO = $this->dataTransferService->buildDTOFor(Room::class, 'create', $payload);
        $createRoomDTO->name = $payload['name'] ?? null;

        $errors = $this->validatorService->validate($createRoomDTO);
        if ($errors) {
            return $this->withValidationErrors($errors, $response);
        }

        $room = $this->roomService->createRoom($user, $createRoomDTO);

        $readRoomDTO = $this->dataTransferService->buildDTOFor(Room::class, 'read', $room);
        $responseData = $this->dataTransferService->serialize($readRoomDTO);
        return $this->withJSON(201, $responseData, $response);
    }

    public function getById(Request $request, Response $response, array $args): Response {
        $id = (int) $args['id'];
        try {
            $room = $this->roomService->getRoomById($id);
        } catch (\Throwable $e) {
            return $this->withError(404, $e, $response);
        }
        $readRoomDTO = $this->dataTransferService->buildDTOFor(Room::class, 'read', $room);
        $responseData = $this->dataTransferService->serialize($readRoomDTO);
        return $this->withJSON(200, $responseData, $response);
    }

    public function listMessages(Request $request, Response $response, array $args): Response {
        $id = (int) $args['id'];
        $messages = $this->roomService->listMessagesForRoom($id);
        $readMessageDTOs = MessageDTOMapper::toReadDTOs($messages);
        $responseData = $this->dataTransferService->serialize($readMessageDTOs);

        return $this->withJSON(200, $responseData, $response);
    }
}
