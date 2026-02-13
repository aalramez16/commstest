<?php
declare(strict_types=1);

namespace CommsTest\DTO\Room;

use CommsTest\DTO\DTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO for creating a Room.
 *
 * The `$owner` is not part of this DTO because it will be derived from
 * the authenticated user attached to the request.
 */
class CreateRoomDTO implements DTOInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public ?string $name;
}
