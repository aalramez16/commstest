<?php
declare(strict_types=1);

namespace CommsTest\Service\EntityService;

use CommsTest\DTO\User\CreateUserDTO;
use CommsTest\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * The User service is responsible for manipulating User entity objects.
 * It can receive User request DTOs and produces User response DTOs.
 */
class UserService {
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new User with the specified provisioner.
     *
     * @param User $provisioner The user provisioning this new user
     * @param CreateUserDTO $dto Data transfer object containing user creation info
     * @return User
     */
    public function createUser(User $provisioner, CreateUserDTO $dto): User {
        $user = new User();
        $user->setName($dto->name);
        $user->setProvisioner($provisioner);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Returns all users ordered by newest first.
     *
     * @return User[]
     */
    public function listUsers(): array {
        return $this->entityManager
            ->getRepository(User::class)
            ->findBy([], ['createdAt' => 'DESC']);
    }

    /**
     * Retrieves a single User by its ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return User|null The user entity if found, or null if not.
     */
    public function getUserById(int $id): ?User {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new \RuntimeException("User not found.");
        }
        return $user;
    }

}