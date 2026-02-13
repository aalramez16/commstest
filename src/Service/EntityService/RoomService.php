<?php
declare(strict_types=1);

namespace CommsTest\Service\EntityService;

use CommsTest\DTO\Room\CreateRoomDTO;
use CommsTest\Entity\Message;
use CommsTest\Entity\Room;
use CommsTest\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * The Room service is responsible for creating and listing Room entities.
 * Accepts Room request DTOs and produces Room response DTOs.
 */
class RoomService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new Room.
     *
     * @param User $owner The authenticated user creating the room.
     * @param CreateRoomDTO $dto Data for the new Room.
     * @return Room
     */
    public function createRoom(User $owner, CreateRoomDTO $dto): Room
    {
        $room = new Room();
        $room->setOwner($owner);
        $room->setName($dto->name);

        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return $room;
    }

    /**
     * Returns all rooms ordered by newest first.
     *
     * @return Room[]
     */
    public function listRooms(): array
    {
        return $this->entityManager
            ->getRepository(Room::class)
            ->findBy([], ['createdAt' => 'DESC']);
    }

    /**
     * Retrieves a single Room by its ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return Room|null The room entity if found, or null if not.
     */
    public function getRoomById(int $id): ?Room {
        $room = $this->entityManager->getRepository(Room::class)->find($id);
        if (!$room) {
            throw new \RuntimeException("Room not found.");
        }
        return $room;
    }

    /**
     * Returns all messages in a room ordered by newest first.
     *
     * @return Message[]
     */
    public function listMessagesForRoom($roomId): array {
        $room = $this->entityManager->getRepository(Room::class)->find($roomId);
        return $this->entityManager->getRepository(Message::class)->findBy(['room' => $room], ['sentAt' => 'DESC']);
    }
}
