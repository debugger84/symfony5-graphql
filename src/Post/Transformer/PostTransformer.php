<?php

declare(strict_types=1);
namespace App\Post\Transformer;

use App\Post\Entity\Post;

class PostTransformer
{
    public function transformPost(Post $post): array
    {
        return [
            'id' => $post->getId()->toString(),
            'content' => $post->getContent(),
            'ownerId' => $post->getOwnerId()->toString(),
        ];
    }
}
