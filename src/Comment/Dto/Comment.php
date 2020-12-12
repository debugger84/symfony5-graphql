<?php

declare(strict_types=1);

namespace App\Comment\Dto;

use App\Infra\Dto\Traits\SerializableDto;
use App\Infra\Dto\Traits\UnSerializableDto;
//need for rest
class Comment implements \JsonSerializable
{
    use UnSerializableDto, SerializableDto;

    private string $id;
    private string $content;
    private string $authorId;
    private string $postId;
    private string $createdAt;

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return string
     */
    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getPostId(): string
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
