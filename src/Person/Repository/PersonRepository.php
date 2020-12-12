<?php

namespace App\Person\Repository;

use App\Person\Entity\Person;
use App\Post\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

use function Doctrine\ORM\QueryBuilder;

//need for rest
class PersonRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Person::class);
    }

    /**
     * @param array<int, string> $ids
     * @return array<int, Post>
     */
    public function getPeopleByIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->in('p.id', ':ids'))
            ->setParameter('ids', $ids, Connection::PARAM_STR_ARRAY);

        return $qb->getQuery()->getResult();
    }
}
