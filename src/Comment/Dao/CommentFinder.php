<?php declare(strict_types=1);

namespace App\Comment\Dao;

use App\Comment\Dto\Comment;
use App\Comment\Dao\Qb\CommentQueryBuilder;
use App\Post\ExternalContracts\LastCommentsFinder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDO\Statement;
use Doctrine\DBAL\Exception;

/**
 * Class CommentFinder
 * @package App\Comment\Dao
 * @psalm-import-type CommentArray
 */
class CommentFinder implements LastCommentsFinder
{
    private Connection $connection;

    /**
     * PostFinder constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array<int, string> $postIds
     * @param int $countPerPost
     * @return array
     * @psalm-return CommentArray
     * @throws Exception
     */
    public function findLastComments(array $postIds, int $countPerPost): array
    {
        $qb = new CommentQueryBuilder($this->connection, 'rank_filter');
        /** @var Statement $stmt */
        $stmt = $qb->selectComment()
            ->fromLastComments($postIds, $countPerPost)
            ->query()
            ->execute()
        ;

        return $stmt->fetchAllAssociative();
    }

    /**
     * @inheritdoc
     * //need for rest
     */
    public function getLastComments(array $postIds, int $countPerPost): array
    {
        $commentsArr = $this->findLastComments($postIds, $countPerPost);
        $result = [];
        foreach ($commentsArr as $item) {
            $result[] = Comment::createFromArray($item);
        }

        return $result;
    }
}
