<?php

namespace App\Repository;

use App\Entity\CuentaPorCobrar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CuentaPorCobrar>
 *
 * @method CuentaPorCobrar|null find($id, $lockMode = null, $lockVersion = null)
 * @method CuentaPorCobrar|null findOneBy(array $criteria, array $orderBy = null)
 * @method CuentaPorCobrar[]    findAll()
 * @method CuentaPorCobrar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentaPorCobrarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CuentaPorCobrar::class);
    }

    public function add(CuentaPorCobrar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CuentaPorCobrar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllRegisters(){
        return $this->createQueryBuilder('c')
            ->select('c, cliente, dni')
            ->innerJoin('c.cliente', 'cliente')
            ->innerJoin('cliente.dni', 'dni')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return CuentaPorCobrar[] Returns an array of CuentaPorCobrar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CuentaPorCobrar
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
