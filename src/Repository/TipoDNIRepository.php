<?php

namespace App\Repository;

use App\Entity\TipoDNI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoDNI|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoDNI|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoDNI[]    findAll()
 * @method TipoDNI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoDNIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoDNI::class);
    }

    // /**
    //  * @return TipoDNI[] Returns an array of TipoDNI objects
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
    public function findOneBySomeField($value): ?TipoDNI
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
