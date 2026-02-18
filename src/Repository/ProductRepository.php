<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findPaginate(?int $size = 10, ?int $page = 1): array
    {
        $offset = ($page - 1) * $size;

        $count = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();


        $products = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($size)
            ->getQuery()
            ->getResult();

        return [
            'products' => $products,
            'count' => $count
        ];
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
