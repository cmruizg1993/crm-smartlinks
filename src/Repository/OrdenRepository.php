<?php

namespace App\Repository;

use App\Entity\Orden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Orden|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orden|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orden[]    findAll()
 * @method Orden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orden::class);
    }
    public function getRangeOfDate($desde, $hasta)
    {
        return $this->createQueryBuilder('o')
            ->select('o, cliente, dni, contrato, estado, tipo, tecnico')
            ->leftJoin('o.Contrato', 'contrato')
            ->leftJoin('contrato.cliente', 'cliente')
            ->leftJoin('cliente.dni','dni')
            ->leftJoin('o.estado', 'estado')
            ->leftJoin('o.tipo', 'tipo')
            ->leftJoin('o.tecnico', 'tecnico')
            ->andWhere('o.fechaEjecucion >= :desde')
            ->andWhere('o.fechaEjecucion <= :hasta')
            ->orderBy('o.fechaEjecucion', 'DESC')
            ->getQuery()
            ->setParameter('desde', $desde)
            ->setParameter('hasta', $hasta)
            ->getResult()
            ;
    }

    // /**
    //  * @return Orden[] Returns an array of Orden objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orden
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
