<?php
declare(strict_types=1);

namespace CommsTest\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'room')]
class Room {
    #[Groups(['room:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Groups(['room:read'])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false)]
    private User $owner;

    #[Groups(['room:read'])]
    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[Groups(['room:read'])]
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    public function __construct() {
        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    public function getId(): int {
        return $this->id;
    }

    public function getOwner(): User {
        return $this->owner;
    }

    public function setOwner(User $owner): self {
        $this->owner = $owner;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }
}
