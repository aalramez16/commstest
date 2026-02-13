<?php
declare(strict_types=1);

namespace CommsTest\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User {

    #[Groups(['user:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Groups(['user:read'])]
    #[ORM\Column(name: 'name', type: 'string', length: 100)]
    private string $name;

    #[Groups(['user:read'])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'provisioner_id', referencedColumnName: 'id', nullable: false)]
    private ?User $provisioner;

    #[ORM\Column(name: 'token', type: 'string', length: 100)]
    private string $token;

    #[Groups(['user:read'])]
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    private function generateToken(): string {
        return base64_encode(random_bytes(32));
    }

    public function __construct() {
        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $this->token = $this->generateToken();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getProvisioner(): ?User {
        return $this->provisioner ?? null;
    }

    public function setProvisioner(User $provisioner): self {
        $this->provisioner = $provisioner;
        return $this;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function regenerateToken(): self {
        $this->token = $this->generateToken();
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $sentAt): self {
        $this->createdAt = $sentAt;
        return $this;
    }
}

