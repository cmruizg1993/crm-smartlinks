<?php

namespace App\Repository;

use App\Entity\DetalleCuentaPorCobrar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetalleCuentaPorCobrar>
 *
 * @method DetalleCuentaPorCobrar|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetalleCuentaPorCobrar|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetalleCuentaPorCobrar[]    findAll()
 * @method DetalleCuentaPorCobrar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetalleCuentaPorCobrarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetalleCuentaPorCobrar::class);
    }

    public function add(DetalleCuentaPorCobrar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DetalleCuentaPorCobrar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DetalleCuentaPorCobrar[] Returns an array of DetalleCuentaPorCobrar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetalleCuentaPorCobrar
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
