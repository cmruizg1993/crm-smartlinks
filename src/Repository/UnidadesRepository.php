<?php

namespace App\Repository;

use App\Entity\Unidades;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Unidades|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unidades|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unidades[]    findAll()
 * @method Unidades[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnidadesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unidades::class);
    }

    // /**
    //  * @return Unidades[] Returns an array of Unidades objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Unidades
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
