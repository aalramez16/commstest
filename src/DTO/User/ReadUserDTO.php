<?php
declare(strict_types=1);

namespace CommsTest\DTO\User;

use CommsTest\DTO\DTOInterface;
use CommsTest\Entity\User;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * DTO for reading a User.
 */
class ReadUserDTO implements DTOInterface {
    #[Groups(['user:read'])]
    public int $id;

    #[Groups(['user:read'])]
    public string $name;

    #[Groups(['user:read'])]
    public string $provisionerName;

    #[Groups(['user:read'])]
    public string|DateTimeInterface $createdAt;

    public function __construct() {}
}
