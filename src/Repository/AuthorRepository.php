<?php
// ============================================
// src/Repository/AuthorRepository.php
// ============================================

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
