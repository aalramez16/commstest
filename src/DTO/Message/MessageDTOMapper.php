<?php
declare(strict_types=1);

namespace CommsTest\DTO\Message;

use CommsTest\Entity\Message;

class MessageDTOMapper {
    public static function toReadDTO(Message $message): ReadMessageDTO {
        $dto = new ReadMessageDTO();
        $dto->id = $message->getId();
        $dto->senderName = $message->getSender()->getName();
        $dto->messageContents = $message->getMessageContents();
        $dto->sentAt = $message->getSentAt();
        return $dto;
    }

    public static function toReadDTORoomAgnostic(Message $message): ReadMessageDTO {
        $dto = new ReadMessageDTO();
        $dto->id = $message->getId();
        $dto->senderName = $message->getSender()->getName();
        $dto->roomName = $message->getRoom()->getName();
        $dto->messageContents = $message->getMessageContents();
        $dto->sentAt = $message->getSentAt();
        return $dto;
    }

    /** 
     * @param Message[] $messages
     * @return ReadMessageDTO[] $dtos
     */
    public static function toReadDTOs(array $messages): array {
        $dtos = [];
        foreach ($messages as $message) {
            $dtos[] = self::toReadDTO($message);
        }
        return $dtos;
    }

    /** 
     * @param Message[] $messages
     * @return ReadMessageDTO[] $dtos
     */
    public static function toReadDTOsRoomAgnostic(array $messages): array {
        $dtos = [];
        foreach ($messages as $message) {
            $dtos[] = self::toReadDTORoomAgnostic($message);
        }
        return $dtos;
    }
}