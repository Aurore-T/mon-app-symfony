<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findPaginate(int $size, int $page): array
    {
        $offset = ($page - 1) * $size;

        $count = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();


        $categories = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($size)
            ->getQuery()
            ->getResult();

        return [
            'categories' => $categories,
            'count' => $count
        ];
    }
}
