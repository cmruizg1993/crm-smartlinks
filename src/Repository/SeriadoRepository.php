<?php

namespace App\Repository;

use App\Entity\Seriado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Seriado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seriado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seriado[]    findAll()
 * @method Seriado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeriadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seriado::class);
    }

    // /**
    //  * @return Seriado[] Returns an array of Seriado objects
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
    public function findOneBySomeField($value): ?Seriado
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
