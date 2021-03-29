<?php

namespace App\Repository;

use App\Entity\ServicioClaro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServicioClaro|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServicioClaro|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServicioClaro[]    findAll()
 * @method ServicioClaro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicioClaroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServicioClaro::class);
    }

    // /**
    //  * @return ServicioClaro[] Returns an array of ServicioClaro objects
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
    public function findOneBySomeField($value): ?ServicioClaro
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
