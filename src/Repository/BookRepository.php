<?php
// ============================================
// src/Repository/BookRepository.php
// ============================================

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchBooks($searchTerm = null, $categoryId = null, $editorId = null, $authorId = null): Query
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.category', 'c')
            ->leftJoin('b.editor', 'e')
            ->leftJoin('b.authors', 'a');

        if ($searchTerm) {
            $qb->andWhere('b.title LIKE :search OR b.description LIKE :search OR b.isbn LIKE :search')
               ->setParameter('search', '%' . $searchTerm . '%');
        }

        if ($categoryId) {
            $qb->andWhere('c.id = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }

        if ($editorId) {
            $qb->andWhere('e.id = :editorId')
               ->setParameter('editorId', $editorId);
        }

        if ($authorId) {
            $qb->andWhere('a.id = :authorId')
               ->setParameter('authorId', $authorId);
        }

        return $qb->orderBy('b.title', 'ASC')
            ->getQuery();
    }

    public function findLatestBooks(int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
