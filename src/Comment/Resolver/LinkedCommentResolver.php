<?php declare(strict_types=1);

namespace App\Comment\Resolver;

use App\Comment\DataLoader\LastCommentsLoader;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class LinkedCommentResolver extends ResolverMap
{
    private LastCommentsLoader $commentsLoader;

    /**
     * LinkedCommentResolver constructor.
     * @param LastCommentsLoader $commentsLoader
     */
    public function __construct(LastCommentsLoader $commentsLoader)
    {
        $this->commentsLoader = $commentsLoader;
    }

    public function map()
    {
        return [
            'Post' => [
                'lastComments' => function ($value, Argument $args) {
                    return $this->commentsLoader->load($value['id'], $args['count']);
                },
            ],
        ];
    }
}
