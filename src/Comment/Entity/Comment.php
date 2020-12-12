<?php

declare(strict_types=1);

namespace App\Comment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="comment", schema="comment")
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private UuidInterface $id;

    /** @ORM\Column(type="text", name="content", nullable=false) */
    private string $content;

    /** @ORM\Column(type="uuid", nullable=false) */
    private UuidInterface $authorId;

    /** @ORM\Column(type="uuid", nullable=false) */
    private UuidInterface $postId;

    /** @ORM\Column(type="datetimetz_immutable", nullable=false) */
    private \DateTimeImmutable $createdAt;

    /**
     * Post constructor.
     * @param string $content
     * @param UuidInterface $authorId
     * @param UuidInterface $postId
     */
    public function __construct(string $content, UuidInterface $authorId, UuidInterface $postId)
    {
        $this->id = Uuid::uuid4();
        $this->content = $content;
        $this->authorId = $authorId;
        $this->createdAt = new \DateTimeImmutable();
        $this->postId = $postId;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return UuidInterface
     */
    public function getAuthorId(): UuidInterface
    {
        return $this->authorId;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
