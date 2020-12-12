<?php declare(strict_types=1);

namespace App\Person\Resolver;

use App\Person\DataLoader\PersonLoader;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

/**
 * Class LinkedPersonResolver
 * @package App\Person\Resolver
 * @psalm-import-type PostArray from \App\Post\Dao\Qb\PostQueryBuilder
 * @psalm-import-type CommentArray from \App\Comment\Dao\Qb\CommentQueryBuilder
 * @see \App\Comment\Dao\Qb\CommentQueryBuilder
 */
class LinkedPersonResolver extends ResolverMap
{
    private PersonLoader $personLoader;

    /**
     * PostResolver constructor.
     * @param PersonLoader $personLoader
     */
    public function __construct(PersonLoader $personLoader)
    {
        $this->personLoader = $personLoader;
    }

    public function map()
    {
        return [
            'Post' => [
                'owner' => function (array $value) {
                    /** @psalm-var PostArray $value */
                    return $this->personLoader->load($value['ownerId']);
                },
            ],
            'Comment' => [
                'author' => function (array $value) {
                    /** @psalm-var CommentArray $value */
                    return $this->personLoader->load($value['authorId']);
                },
            ],
        ];
    }
}
