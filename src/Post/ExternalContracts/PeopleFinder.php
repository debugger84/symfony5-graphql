<?php

declare(strict_types=1);

namespace App\Post\ExternalContracts;

use App\Comment\Dto\Comment;
//need for rest
interface PeopleFinder
{
    /**
     * @param array<int, string> $personIds
     * @return array<int, Comment>
     */
    public function getPeople(array $personIds): array;
}
