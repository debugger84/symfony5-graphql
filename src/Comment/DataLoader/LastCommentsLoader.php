<?php declare(strict_types=1);

namespace App\Comment\DataLoader;

use App\Comment\Dao\CommentFinder;
use App\Infra\DataLoader\AbstractDataLoader;
use GraphQL\Executor\Promise\Promise;
use Overblog\PromiseAdapter\PromiseAdapterInterface;

class LastCommentsLoader extends AbstractDataLoader
{
    private CommentFinder $commentFinder;

    /**
     * LastCommentLoader constructor.
     * @param CommentFinder $commentFinder
     * @param PromiseAdapterInterface $promiseAdapter
     */
    public function __construct(
        CommentFinder $commentFinder,
        PromiseAdapterInterface $promiseAdapter
    ) {
        parent::__construct($promiseAdapter);
        $this->commentFinder = $commentFinder;
    }

    public function load(string $postId, int $count): Promise
    {
        return $this->getDataLoader($this->findValues($count), true)->load($postId);
    }

    protected function findValues(int $count): callable
    {
        return function ($keys) use ($count) {
            return $this->commentFinder->findLastComments($keys, $count);
        };
    }

    protected function getKeyFieldName(): string
    {
        return 'postId';
    }
}
