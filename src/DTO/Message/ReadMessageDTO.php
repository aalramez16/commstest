<?php
declare(strict_types=1);

namespace CommsTest\DTO\Message;

use CommsTest\DTO\DTOInterface;
use CommsTest\Entity\User;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Groups;

class ReadMessageDTO implements DTOInterface {
    #[Groups(['message:read'])]
    public int $id;

    #[Groups(['message:read'])]
    public string $senderName;

    #[Groups(['message:read'])]
    public string $roomName;

    #[Groups(['message:read'])]
    public string $messageContents;

    #[Groups(['message:read'])]
    public string | DateTimeInterface $sentAt;

    public function __construct() {}
}