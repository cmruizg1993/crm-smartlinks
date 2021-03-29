<?php

namespace App\Repository;

use App\Entity\Temp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Temp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Temp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Temp[]    findAll()
 * @method Temp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Temp::class);
    }

    // /**
    //  * @return Temp[] Returns an array of Temp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Temp
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
