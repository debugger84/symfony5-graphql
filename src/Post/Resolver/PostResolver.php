<?php declare(strict_types=1);

namespace App\Post\Resolver;

use App\Post\Dao\PostFinder;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

/**
 * Class PostResolver
 * @package App\Post\Resolver
 * @psalm-import-type PostArray from \App\Post\Dao\Qb\PostQueryBuilder
 */
class PostResolver extends ResolverMap
{
    private PostFinder $postFinder;

    /**
     * PostResolver constructor.
     * @param PostFinder $postFinder
     */
    public function __construct(PostFinder $postFinder)
    {
        $this->postFinder = $postFinder;
    }

    public function map()
    {
        return [
            'Query' => [
                /** @psalm-param  PostArray $value */
                'posts' => function (?array $value, Argument $args) {
                    $paginator = new Paginator(function (int $offset, int $limit) {
                        return $this->postFinder->findAll($offset, $limit);
                    });

                    return $paginator->auto($args, function() {
                        return $this->postFinder->findAllCount();
                    });
                },
            ],
        ];
    }
}
