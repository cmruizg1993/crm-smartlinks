<?php

namespace App\Repository;

use App\Entity\NoSeriado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoSeriado|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoSeriado|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoSeriado[]    findAll()
 * @method NoSeriado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoSeriadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoSeriado::class);
    }

    // /**
    //  * @return NoSeriado[] Returns an array of NoSeriado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NoSeriado
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
