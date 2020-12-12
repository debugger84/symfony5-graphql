<?php

namespace App\Post\Repository;

use App\Post\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Post::class);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array<int, Post>
     */
    public function getAllPosts(int $offset, int $limit): array
    {
        $qb = $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function getAllPostsCount(): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
