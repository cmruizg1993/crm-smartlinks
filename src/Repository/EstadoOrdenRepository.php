<?php

namespace App\Repository;

use App\Entity\EstadoOrden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstadoOrden|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoOrden|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoOrden[]    findAll()
 * @method EstadoOrden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoOrdenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoOrden::class);
    }

    // /**
    //  * @return EstadoOrden[] Returns an array of EstadoOrden objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstadoOrden
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
