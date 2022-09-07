<?php

namespace App\Entity\TwitterAccount;

use DateTime;
use DateTimeImmutable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class TwitterAccount
{
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $actualId;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private null|string $lastTweetId;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private null|DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private null|DateTime $deletedAt;

    public function __construct(int $actualId, string $username, ?int $lastTweetId)
    {
        $this->actualId = (string) $actualId;
        $this->username = $username;
        $this->lastTweetId = (string) $lastTweetId;

        $this->createdAt = new DateTime();
    }

    public function id(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function actualId()
    {
        return $this->actualId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function lastTweetId(): null|string
    {
        return $this->lastTweetId;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): null|DateTime
    {
        return $this->updatedAt;
    }

    public function deletedAt(): null|DateTime
    {
        return $this->deletedAt;
    }

    public function updateLastTweetId(?int $lastTweetId): void
    {
        $this->lastTweetId = $lastTweetId;
    }

    public function delete(): void
    {
        $this->deletedAt = new DateTime();
    }
}