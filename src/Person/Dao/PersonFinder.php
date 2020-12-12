<?php declare(strict_types=1);

namespace App\Person\Dao;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

use function Doctrine\DBAL\Query\QueryBuilder;

class PersonFinder
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

    public function findByIds(array $ids): array
    {
        $qb = $this->connection->createQueryBuilder();

        $data = $qb->select('*')
            ->from('person.person')
            ->where($qb->expr()->in('id', ':ids'))
            ->setParameter('ids', $ids, Connection::PARAM_STR_ARRAY)
            ->execute()
            ->fetchAllAssociative()
        ;
        return $data;
    }
}
