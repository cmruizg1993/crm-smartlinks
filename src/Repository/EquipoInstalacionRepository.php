<?php

namespace App\Repository;

use App\Entity\EquipoInstalacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EquipoInstalacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipoInstalacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipoInstalacion[]    findAll()
 * @method EquipoInstalacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipoInstalacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipoInstalacion::class);
    }

    // /**
    //  * @return EquipoInstalacion[] Returns an array of EquipoInstalacion objects
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
    public function findOneBySomeField($value): ?EquipoInstalacion
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
