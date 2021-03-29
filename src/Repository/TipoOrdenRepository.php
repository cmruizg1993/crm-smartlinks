<?php

namespace App\Repository;

use App\Entity\TipoOrden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoOrden|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOrden|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOrden[]    findAll()
 * @method TipoOrden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOrdenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOrden::class);
    }

    // /**
    //  * @return TipoOrden[] Returns an array of TipoOrden objects
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
    public function findOneBySomeField($value): ?TipoOrden
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
