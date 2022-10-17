<?php

namespace App\Repository;

use App\Entity\OpcionCatalogo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpcionCatalogo|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpcionCatalogo|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpcionCatalogo[]    findAll()
 * @method OpcionCatalogo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpcionCatalogoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpcionCatalogo::class);
    }

    // /**
    //  * @return OpcionCatalogo[] Returns an array of OpcionCatalogo objects
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
    public function findOneBySomeField($value): ?OpcionCatalogo
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
