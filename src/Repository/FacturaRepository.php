<?php

namespace App\Repository;

use App\Entity\Factura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Factura|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factura|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factura[]    findAll()
 * @method Factura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Factura::class);
    }
    public function getAll()
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.cliente', 'cliente')
            ->leftJoin('cliente.dni', 'dni')
            ->leftJoin('f.contrato','contrato')
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function getRangeOfDate($desde, $hasta)
    {
        return $this->createQueryBuilder('f')
            ->select('f, cliente, dni, contrato')
            ->leftJoin('f.cliente', 'cliente')
            ->leftJoin('cliente.dni', 'dni')
            ->leftJoin('f.contrato','contrato')
            ->andWhere('f.fecha >= :desde')
            ->andWhere('f.fecha <= :hasta')
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->setParameter('desde', $desde)
            ->setParameter('hasta', $hasta)
            ->getResult()
            ;
    }
    public function obtenerSecuencial($punto_emision_id): int
    {
        /* @var $last Factura|null */

        $last = $this->createQueryBuilder('f')
            ->innerJoin('f.puntoEmision', 'p', 'WITH', 'p.id = :puntoEmision')
            ->orderBy('f.secuencial', 'DESC')
            ->setMaxResults(1)
            ->setParameter('puntoEmision', $punto_emision_id)
            ->getQuery()
            ->getOneOrNullResult();
        return $last ? (int)$last->getSecuencial() + 1: 1;
    }
    public function getPage($page, $pageLength)
    {
        if($page <= 0 || $pageLength <= 0) return null;
        $first = ($page-1)*$pageLength;
        $query = $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setFirstResult($first)
            ->setMaxResults($pageLength)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function obtenerNumeroDeFacturas()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('count(f.id)');
        $count = $qb->getQuery()->getSingleScalarResult();
        return (int)$count;
    }
    public function obtenerSecuencialPorComprobante($codigo){
        return $this->createQueryBuilder('f')
                    ->innerJoin('f.puntoEmision', 'p')
                    ->innerJoin('p.tipoComprobante', 'c', 'WITH', 'c.codigo = :val')
                    ->setParameter('val', $codigo)
                    ->orderBy('p.codigo', 'DESC')
                    ->orderBy('f.secuencial', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
    public function importarCodigoComprobante(){
        $em = $this->getEntityManager();
        $sql = "UPDATE factura f SET f.tipo_comprobante = ( SELECT t.codigo FROM punto_emision p INNER JOIN tipo_comprobante t ON p.tipo_comprobante_id = t.id WHERE p.id = f.punto_emision_id LIMIT 1 )";
        $stmt = $em->getConnection()->prepare($sql);
        return $stmt->executeStatement();
    }
    // /**
    //  * @return Factura[] Returns an array of Factura objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Factura
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
