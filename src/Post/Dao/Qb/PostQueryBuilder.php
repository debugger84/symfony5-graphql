<?php

declare(strict_types=1);

namespace App\Post\Dao\Qb;

use App\Infra\Dao\Traits\ColumnsConverter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class PostQueryBuilder
 * @package App\Post\Dao\Qb
 * @psalm-type PostArray = array{id: string, content: string, ownerId: string}
 */
final class PostQueryBuilder
{
    use ColumnsConverter;

    private string $alias;
    private QueryBuilder $qb;

    /**
     * CommentQueryBuilder constructor.
     * @param Connection $connection
     * @param string $alias
     */
    public function __construct(
        Connection $connection,
        string $alias = 'post'
    ) {
        $this->alias = $alias;
        $this->qb = $connection->createQueryBuilder();
        $this->qb->from('post.post', $alias);
    }

    public function selectPost(): self
    {
        $this->qb->select($this->convertColumnsToString($this->getSelectValues()));
        return $this;
    }

    public function selectCount(): self
    {
        $this->qb->select('count(*)');
        return $this;
    }

    public function query(): QueryBuilder
    {
        return $this->qb;
    }

    /**
     * @return array<string, string>
     */
    private function getSelectValues(): array
    {
        return [
            'id' => 'id',
            'content' => 'content',
            'owner_id' => 'ownerId',
        ];
    }
}
