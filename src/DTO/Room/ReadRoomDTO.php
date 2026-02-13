<?php
declare(strict_types=1);

namespace CommsTest\DTO\Room;

use CommsTest\DTO\DTOInterface;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * DTO for reading a Room.
 */
class ReadRoomDTO implements DTOInterface
{
    #[Groups(['room:read'])]
    public int $id;

    #[Groups(['room:read'])]
    public string $ownerName;

    #[Groups(['room:read'])]
    public string $name;

    #[Groups(['room:read'])]
    public string|DateTimeInterface $createdAt;

    public function __construct() {}
}
