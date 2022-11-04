<?php

namespace App\Repository;

use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cliente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cliente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cliente[]    findAll()
 * @method Cliente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cliente::class);
    }
    public function getAll()
    {
        return $this->createQueryBuilder('c')
            ->select('c, dni')
            ->innerJoin('c.dni', 'dni')
            ->orderBy('c.nombres', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Cliente[] Returns an array of Cliente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByNumeroDni($value): ?Cliente
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.dni', 'd', Join::WITH, 'd.numero = :ci')
            ->setParameter('ci', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
