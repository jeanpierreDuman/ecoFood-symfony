<?php

namespace App\Repository;

use App\Entity\FoodProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FoodProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodProduct[]    findAll()
 * @method FoodProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodProduct::class);
    }

    // /**
    //  * @return FoodProduct[] Returns an array of FoodProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FoodProduct
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
