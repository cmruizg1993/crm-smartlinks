<?php

namespace App\Repository;

use App\Entity\PuntoEmision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PuntoEmision|null find($id, $lockMode = null, $lockVersion = null)
 * @method PuntoEmision|null findOneBy(array $criteria, array $orderBy = null)
 * @method PuntoEmision[]    findAll()
 * @method PuntoEmision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuntoEmisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PuntoEmision::class);
    }

    // /**
    //  * @return PuntoEmision[] Returns an array of PuntoEmision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PuntoEmision
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
