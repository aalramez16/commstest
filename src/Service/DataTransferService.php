<?php
declare(strict_types=1);

namespace CommsTest\Service;

use CommsTest\DTO\DTOInterface;
use Symfony\Component\Serializer\Serializer;
use CommsTest\DTO\Message\CreateMessageDTO;
use CommsTest\DTO\Message\ReadMessageDTO;
use CommsTest\DTO\Room\CreateRoomDTO;
use CommsTest\DTO\Room\ReadRoomDTO;
use CommsTest\DTO\User\CreateUserDTO;
use CommsTest\DTO\User\ReadUserDTO;
use CommsTest\Entity\Message;
use CommsTest\Entity\Room;
use CommsTest\Entity\User;

class DataTransferService {
    private Serializer $serializer;

    public function __construct(Serializer $serializer) {
        $this->serializer = $serializer;
    }

    /**
     * Any DTOs must be mapped here in order to be usable.
     */
    private array $dtoMap = [
        Message::class => [
            'create' => CreateMessageDTO::class,
            'read'  => ReadMessageDTO::class,
        ],
        Room::class => [
            'create' => CreateRoomDTO::class,
            'read'  => ReadRoomDTO::class,
        ],
        User::class => [
            'create' => CreateUserDTO::class,
            'read'  => ReadUserDTO::class,
        ]
    ];

    /** 
     * Copies data from an input array into the DTO.
     * Will cast to proper type if necessary.
     * Currently works for int, string, float, and bool.
     */
    private function populateDTO(DTOInterface $dto, array $data) {
        foreach ($data as $prop => $value) {
            if (!property_exists($dto, $prop)) {
                continue;
            }

            $reflection = new \ReflectionProperty($dto, $prop);
            $type = $reflection->getType();

            if ($type !== null) {

                $nullable = $type->allowsNull();

                if ($value === null && !$nullable) {
                    throw new \InvalidArgumentException("Property $prop cannot be null");
                }

                $typeNames = [];

                if ($type instanceof \ReflectionNamedType) {
                    $typeNames[] = $type->getName();
                }

                if ($type instanceof \ReflectionUnionType) {
                    foreach ($type->getTypes() as $unionType) {
                        $typeNames[] = $unionType->getName();
                    }
                }

                // Remove null from union types
                $typeNames = array_filter($typeNames, fn($t) => $t !== 'null');

                if ($value !== null && count($typeNames) > 0) {
                    $primaryType = $typeNames[0];

                    switch ($primaryType) {
                        case 'int':
                            $value = (int) $value;
                            break;
                        case 'float':
                            $value = (float) $value;
                            break;
                        case 'bool':
                            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                            break;
                        case 'string':
                            $value = (string) $value;
                            break;
                    }
                }
            }

            $dto->$prop = $value;
        }

        return $dto;
    }



    /**
     * Iteratively calls `buildDTOFor()` on each indexed value. returns a new array of DTOInterface objects.
     * @return DTOInterface[]
     */
    public function buildDTOArrayFor(string $classString, string $crudOperation, array $dataArray): array {
        $dtoArray = [];

        foreach ($dataArray as $data) {
            $dtoArray[] = $this->buildDTOFor($classString, $crudOperation, $data);
        }

        return $dtoArray;
    }

    /**
     * Builds a Request DTO for an entity based on the data passed into the function. 
     * 
     * @example
     * Example utilization:
     * 
     *          $this->dataTransferService->buildDTOFor(
     *              Message::class,
     *              ['contents' => 'Hello World!'],
     *              'create'
     *          );
     * 
     */
    public function buildDTOFor(string $classString, string $crudOperation, array|object $data = []): DTOInterface {
        if (!isset($this->dtoMap[$classString][$crudOperation])) {
            throw new \InvalidArgumentException(
                "No DTO mapped for {$classString} with operation {$crudOperation}."
            );
        }
        $dtoClass = $this->dtoMap[$classString][$crudOperation];
        $dto = new $dtoClass();
        if (!is_array($data)) {
            $data = $this->serializer->normalize($data);
        }
        $this->populateDTO($dto, $data);
        return $dto;
    }


    public function serialize(array | object $object, string $format = 'json'): string {
        return $this->serializer->serialize($object, $format);
    }
}