<?php

declare(strict_types=1);

namespace CommsTest\DTO\Message;

use CommsTest\DTO\DTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This DTO contains the fields & validation required to create a message.
 * 
 * The `$sender` is not part of this DTO because it is derived from the
 * authenticated user attached to the request by {@see \CommsTest\Middleware\AuthMiddleware}.
 * 
 *      $request = $request->withAttribute('user', $user);
 */
class CreateMessageDTO implements DTOInterface {
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    public ?string $contents;
}