<?php

namespace App\Repository;

use App\Entity\CategoriaServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriaServicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaServicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaServicio[]    findAll()
 * @method CategoriaServicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaServicio::class);
    }

    // /**
    //  * @return CategoriaServicio[] Returns an array of CategoriaServicio objects
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

    /*
    public function findOneBySomeField($value): ?CategoriaServicio
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
