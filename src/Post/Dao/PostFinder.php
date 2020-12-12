<?php declare(strict_types=1);

namespace App\Post\Dao;

use App\Post\Dao\Qb\PostQueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Driver\PDO\Statement;
use Doctrine\DBAL\Exception;

/**
 * Class PostFinder
 * @package App\Post\Dao
 * @psalm-import-type PostArray from \App\Post\Dao\Qb\PostQueryBuilder
 */
class PostFinder
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
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws DriverException
     * @throws Exception
     */
    public function findAll(int $offset, int $limit): array
    {
        $qb = new PostQueryBuilder($this->connection);
        /** @var Statement $stmt */
        $stmt = $qb->selectPost()
            ->query()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->execute()
        ;

        return $stmt->fetchAllAssociative();
    }

    /**
     * @return int
     * @throws DriverException
     * @throws Exception
     */
    public function findAllCount(): int
    {
        $qb = new PostQueryBuilder($this->connection);
        /** @var Statement $stmt */
        $stmt = $qb->selectPost()
            ->selectCount()
            ->query()
            ->execute()
        ;

        /** @var array<int,int> $data */
        $data = $stmt->fetchFirstColumn();
        return $data[0];
    }
}
