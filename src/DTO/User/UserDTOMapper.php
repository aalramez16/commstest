<?php
declare(strict_types=1);

namespace CommsTest\DTO\User;

use CommsTest\Entity\User;

class UserDTOMapper {
    public static function toReadDTO(User $user): ReadUserDTO {
        $dto = new ReadUserDTO();
        $dto->id = $user->getId();
        $dto->name = $user->getName();
        $dto->provisionerName = $user->getProvisioner() ? $user->getProvisioner()->getName() : 'Self-provisioned';
        $dto->createdAt = $user->getCreatedAt();
        return $dto;
    }

    /** 
     * @param User[] $users
     * @return ReadUserDTO[] $dtos
     */
    public static function toReadDTOs(array $users): array {
        $dtos = [];
        foreach ($users as $user) {
            $dtos[] = self::toReadDTO($user);
        }
        return $dtos;
    }
}