<?php

declare(strict_types=1);

namespace App\Comment\Dao\Qb;

use App\Infra\Dao\Traits\ColumnsConverter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class CommentQueryBuilder
 * @package App\Comment\Dao\Qb
 * @psalm-type CommentArray = array{id: string, content: string, authorId: string, postId: string, createdAt: string}
 */
final class CommentQueryBuilder
{
    use ColumnsConverter;

    private string $alias;
    private QueryBuilder $qb;

    /**
     * @return array<string, string>
     */
    private function getSelectValues(): array
    {
        return [
            'id' => 'id',
            'content' => 'content',
            'author_id' => 'authorId',
            'post_id' => 'postId',
            'created_at' => 'createdAt',
        ];
    }

    /**
     * CommentQueryBuilder constructor.
     * @param Connection $connection
     * @param string $alias
     */
    public function __construct(
        Connection $connection,
        string $alias = 'comment'
    ) {
        $this->alias = $alias;
        $this->qb = $connection->createQueryBuilder();
        $this->qb->from('comment.comment', $alias);
    }

    /**
     * @return $this
     */
    public function selectComment(): self
    {
        $this->qb->select($this->getSelect());
        return $this;
    }

    public function fromLastComments(array $postIds, int $countPerPost): self
    {
        $this->qb->from('(SELECT *, 
                rank() OVER (
        PARTITION BY post_id
                    ORDER BY created_at DESC
                ) as r
                FROM comment.comment
                WHERE post_id IN (:postIds))', $this->alias);
        $this->qb->andWhere($this->qb->expr()->lte($this->alias . '.r', $countPerPost));
        $this->qb->setParameter('postIds', $postIds, Connection::PARAM_STR_ARRAY);

        return $this;
    }

    public function query(): QueryBuilder
    {
        return $this->qb;
    }

    private function getSelect(): string
    {
        $columns = $this->getSelectValues();
        return $this->convertColumnsToString($columns);
    }
}
