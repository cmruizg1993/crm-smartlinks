<?php

namespace App\Repository;

use App\Entity\DNI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DNI|null find($id, $lockMode = null, $lockVersion = null)
 * @method DNI|null findOneBy(array $criteria, array $orderBy = null)
 * @method DNI[]    findAll()
 * @method DNI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DniRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DNI::class);
    }

    // /**
    //  * @return DNI[] Returns an array of DNI objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DNI
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
