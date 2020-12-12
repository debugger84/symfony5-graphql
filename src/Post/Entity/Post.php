<?php

declare(strict_types=1);

namespace App\Post\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="post", schema="post")
 * @ORM\Entity()
 */
class Post
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
    private UuidInterface $ownerId;

    /**
     * Post constructor.
     * @param string $content
     * @param UuidInterface $ownerId
     */
    public function __construct(string $content, UuidInterface $ownerId)
    {
        $this->id = Uuid::uuid4();
        $this->content = $content;
        $this->ownerId = $ownerId;
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
    public function getOwnerId(): UuidInterface
    {
        return $this->ownerId;
    }
}
