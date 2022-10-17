<?php

namespace App\Repository;

use App\Entity\Contrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrato[]    findAll()
 * @method Contrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrato::class);
    }
    /**
    * @return Parroquia[] Returns an array of Parroquia objects
    *
    */
    public function findByParam($value)
    {
        $em = $this->getEntityManager();
        /* @var $query QueryBuilder */
        $query = $em->createQuery("SELECT Contrato,cli FROM App\Entity\Contrato Contrato
        INNER JOIN Contrato.cliente cli  
        WHERE Contrato.numero LIKE :param
        OR cli.nombres LIKE :param");
        $query->setParameter("param","%$value%");
        $data = $query->getResult();
        return $data;
       
    }
    public function findAllRegisters()
    {
        $em = $this->getEntityManager();
        /* @var $query QueryBuilder */
        $query = $em->createQuery("SELECT Contrato,cli FROM App\Entity\Contrato Contrato
        INNER JOIN Contrato.cliente cli
        ORDER BY Contrato.id ASC");
        $data = $query->getResult();
        return $data;

    }

    // /**
    //  * @return Contrato[] Returns an array of Contrato objects
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
    public function findOneBySomeField($value): ?Contrato
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
