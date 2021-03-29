<?php

namespace App\Repository;

use App\Entity\SAN;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SAN|null find($id, $lockMode = null, $lockVersion = null)
 * @method SAN|null findOneBy(array $criteria, array $orderBy = null)
 * @method SAN[]    findAll()
 * @method SAN[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SANRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SAN::class);
    }

    // /**
    //  * @return SAN[] Returns an array of SAN objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SAN
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
