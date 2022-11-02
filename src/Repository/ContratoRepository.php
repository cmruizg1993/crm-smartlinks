<?php

namespace App\Repository;

use App\Entity\Contrato;
use App\Entity\EstadoContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
        $query = $em->createQuery("SELECT Contrato,cli,f,est FROM App\Entity\Contrato Contrato
        LEFT JOIN Contrato.facturas f
        INNER JOIN Contrato.cliente cli
        LEFT JOIN Contrato.estados est
        INNER JOIN cli.dni dni  
        WHERE Contrato.numero LIKE :param OR cli.nombres LIKE :param OR dni.numero LIKE :param
        ORDER BY f.anioPago DESC,f.mesPago DESC, est.fecha DESC");
        $query->setParameter("param","%$value%");
        $data = $query->getResult();
        return $data;
       
    }
    public function findAllRegisters()
    {
        $em = $this->getEntityManager();
        $rsm = new Query\ResultSetMappingBuilder($em);
        $sql = "SELECT MIN(x.id), x.*, 
                        cli.nombres, cli.direccion as direccion_cli, cli.vendedor_id as vendedor_cli, cli.parroquia_id as parroquia_cli, 
                        est.id as estado_pk, est.fecha as fecha_estado ,est.estado_id, 
                        op.id as id_opcion, op.texto, op.css_class FROM contrato x 
                JOIN 
                    (SELECT p.numero, MAX(version) AS max_version FROM contrato p GROUP BY p.numero) y 
                    ON y.numero = x.numero AND y.max_version = x.version 
                INNER JOIN cliente cli ON x.cliente_id = cli.id
                LEFT JOIN estado_contrato est ON est.contrato_id = x.id
                LEFT JOIN opcion_catalogo op ON op.id = est.estado_id
                GROUP BY x.numero, x.version";
        $rsm->addRootEntityFromClassMetadata('App\Entity\Contrato', 'x');
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\Cliente', 'cli', 'x', 'cliente',
            ['id'=>'cliente_id', 'direccion'=>'direccion_cli', 'vendedor_id'=>'vendedor_cli', 'parroquia_id'=>'parroquia_cli']);
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\EstadoContrato', 'est', 'x', 'estados',
            ['id'=>'estado_pk', 'fecha'=>'fecha_estado']);
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\OpcionCatalogo', 'op', 'est', 'estado',
            ['id'=>'id_opcion']);
        $query = $em->createNativeQuery($sql, $rsm);
        $result = $query->getResult();
        return $result;

        /* @var $query QueryBuilder */
        $query = $em->createQuery("
        SELECT c, cli, est, op FROM App\Entity\Contrato c
        INNER JOIN c.cliente cli
        LEFT JOIN c.estados est
        LEFT JOIN est.estado op
        ORDER BY c.version ASC");

        /* @var $query Query */
        $query = $this->createQueryBuilder('c')
            ->innerJoin('c.cliente', 'cli')
            ->leftJoin('c.estados', 'est')
            ->leftJoin('est.estado', 'op')
            ->orderBy('c.version', 'ASC')
            ->getQuery();

        $data = $query->getResult();
        return $data;

    }
    public function generarCorte()
    {
        $em = $this->getEntityManager();
        /* @var $query Query */
        $query = $em->createQuery("UPDATE App\Entity\Contrato c SET c.estado = :estado WHERE c.estado = :inpago ");

        return $query
                    ->setParameter('estado', EstadoContrato::CORTADO)
                    ->setParameter('inpago', EstadoContrato::INPAGO)
                    ->execute();
    }
    public function marcarInpagos()
    {
        $em = $this->getEntityManager();
        /* @var $query Query */
        $query = $em->createQuery("UPDATE App\Entity\Contrato c SET c.estado = :estado WHERE c.estado = :activo ");

        return $query
            ->setParameter('estado', EstadoContrato::INPAGO)
            ->setParameter('activo', EstadoContrato::ACTIVO)
            ->execute();
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
