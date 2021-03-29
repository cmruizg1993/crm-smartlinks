<?php

namespace App\Repository;

use App\Entity\MensajeWtp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MensajeWtp|null find($id, $lockMode = null, $lockVersion = null)
 * @method MensajeWtp|null findOneBy(array $criteria, array $orderBy = null)
 * @method MensajeWtp[]    findAll()
 * @method MensajeWtp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MensajeWtpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MensajeWtp::class);
    }

    // /**
    //  * @return MensajeWtp[] Returns an array of MensajeWtp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MensajeWtp
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
