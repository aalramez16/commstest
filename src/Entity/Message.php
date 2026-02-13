<?php
declare(strict_types=1);

namespace CommsTest\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'message')]
class Message {

    #[Groups(['message:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Groups(['message:read'])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'sender_id', referencedColumnName: 'id', nullable: false)]
    private User $sender;

    #[Groups(['message:read'])]
    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[ORM\JoinColumn(name: 'room_id', referencedColumnName: 'id', nullable: false)]
    private Room $room;

    #[Groups(['message:read'])]
    #[ORM\Column(name: 'message_contents', type: 'string')]
    private string $messageContents;

    #[Groups(['message:read'])]
    #[ORM\Column(name: 'sent_at', type: 'datetime_immutable')]
    private DateTimeInterface $sentAt;

    public function __construct() {
        $this->sentAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    public function getId(): int {
        return $this->id;
    }

    public function getSender(): User {
        return $this->sender;
    }

    public function setSender(User $sender): self {
        $this->sender = $sender;
        return $this;
    }

    public function getRoom(): Room {
        return $this->room;
    }

    public function setRoom(Room $room): self {
        $this->room = $room;
        return $this;
    }

    public function getMessageContents(): string {
        return $this->messageContents;
    }

    public function setMessageContents(string $messageContents): self {
        $this->messageContents = $messageContents;
        return $this;
    }

    public function getSentAt(): DateTimeInterface {
        return $this->sentAt;
    }

    public function setSentAt(DateTimeInterface $sentAt): self {
        $this->sentAt = $sentAt;
        return $this;
    }
}

