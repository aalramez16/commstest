<?php
declare(strict_types=1);

namespace CommsTest\DTO\User;

use CommsTest\DTO\DTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO for creating a User.
 *
 * The `$provisioner` is not part of this DTO because it is derived from the
 * authenticated user attached to the request.
 *
 *      $request = $request->withAttribute('user', $provisioner);
 */
class CreateUserDTO implements DTOInterface {
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public ?string $name;
}
