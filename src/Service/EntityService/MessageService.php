<?php
declare(strict_types=1);

namespace CommsTest\Service\EntityService;

use CommsTest\DTO\Message\CreateMessageDTO;
use CommsTest\Entity\Message;
use CommsTest\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * The Message service is responsible for manipulating Message entity objects. it can receive Message request DTO's
 * and produces Message response DTO's
 */
class MessageService {
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user the user creating the message
     * @param CreateMessageDTO $dto data transfer object for creating messages
     * @return Message
     */
    public function createMessage(User $user, CreateMessageDTO $dto): Message {
        $message = new Message();
        $message->setSender($user);
        $message->setMessageContents($dto->contents);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }

    /** @return Message[] */
    public function listMessages(): array {
        $messages = $this->entityManager->getRepository(Message::class)->findBy([], ['sentAt' => 'DESC']);
        return $messages;
    }

    /**
     * Retrieves a single Message by its ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return Message|null The message entity if found, or null if not.
     */
    public function getMessageById(int $id): ?Message {
        $message = $this->entityManager->getRepository(Message::class)->find($id);
        if (!$message) {
            throw new \RuntimeException("Message not found.");
        }
        return $message;
    }
}