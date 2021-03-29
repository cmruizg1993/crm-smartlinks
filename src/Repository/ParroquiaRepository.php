<?php

namespace App\Repository;

use App\Entity\Parroquia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parroquia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parroquia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parroquia[]    findAll()
 * @method Parroquia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParroquiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parroquia::class);
    }

    /**
    * @return Parroquia[] Returns an array of Parroquia objects
    *
    */
    public function findByParam($value)
    {
        $em = $this->getEntityManager();
        /* @var $query QueryBuilder */
        $query = $em->createQuery("SELECT p,c,pr FROM App\Entity\Parroquia p 
        INNER JOIN p.canton c 
        INNER JOIN c.provincia pr 
        WHERE c.nombre LIKE :param
        OR p.nombre LIKE :param
        OR pr.nombre LIKE :param");
        $query->setParameter("param","%$value%");
        $data = $query->getResult();
        return $data;
       /* $qb = $this->createQueryBuilder('p');
        return $qb
            ->innerJoin('p.canton','c', Join::ON)
            ->innerJoin('c.provincia','pr', Join::ON)
            ->where('p.nombre LIKE :param')
            ->orWhere('c.nombre LIKE :param')
            ->orWhere('pr.nombre LIKE :param')
            ->setParameter('param', "%$value%")
            ->getQuery()->getResult();*/
            //->andWhere('p.exampleField = :val')
            //->setParameter('val', $value)
            //->orderBy('p.id', 'ASC')
            //->setMaxResults(100)
            //->getQuery()
            //->getResult()
            //;
    }


    /*
    public function findOneBySomeField($value): ?Parroquia
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
