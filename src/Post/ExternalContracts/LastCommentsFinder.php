<?php

declare(strict_types=1);

namespace App\Post\ExternalContracts;

use App\Comment\Dto\Comment;
//need for rest
interface LastCommentsFinder
{
    /**
     * @param array<int, string> $postIds
     * @param int $countPerPost
     * @return array<int, Comment>
     */
    public function getLastComments(array $postIds, int $countPerPost): array;
}
