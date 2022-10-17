<?php

namespace App\Repository;

use App\Entity\Colaborador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Colaborador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colaborador|null findOneBy(array $criteria, array $orderBy = null)
 */
class ColaboradorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Colaborador::class);
    }

    /**
     * @return Colaborador[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAll(){
        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.usuario', 'u', 'WITH', 'c.id = u.colaborador')
            ->getQuery();
        return $query->getOneOrNullResult();
    }
    /**
     * @return Colaborador[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null){
        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.usuario', 'u', 'WITH', 'c.id = u.colaborador')
            ->getQuery();
        return $query->getOneOrNullResult();
    }

    // /**
    //  * @return Colaborador[] Returns an array of Colaborador objects
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
    public function findOneBySomeField($value): ?Colaborador
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
