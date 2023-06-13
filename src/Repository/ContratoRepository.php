<?php

namespace App\Repository;

use App\Entity\Contrato;
use App\Entity\EstadoContrato;
use App\Entity\OpcionCatalogo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
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
    private $opcionCatalogoRepository;
    public function __construct(ManagerRegistry $registry, OpcionCatalogoRepository $opcionCatalogoRepository)
    {
        $this->opcionCatalogoRepository = $opcionCatalogoRepository;
        parent::__construct($registry, Contrato::class);
    }
    /**
    * @return Parroquia[] Returns an array of Parroquia objects
    *
    */
    public function findByParam($value)
    {
        $fecha = (new \DateTime())->format('Y-m-d');
        $em = $this->getEntityManager();
        /* @var $query QueryBuilder */
        $query = $em->createQuery("
        SELECT Contrato,cli,f,est, dni, plan, deuda, cuota FROM App\Entity\Contrato Contrato
        LEFT JOIN Contrato.facturas f WITH (f.estadoSri != 'ANULADA' OR f.estadoSri IS NULL)
        INNER JOIN Contrato.cliente cli
        INNER JOIN cli.dni dni
        LEFT JOIN cli.deudas deuda WITH deuda.abono IS NULL OR deuda.abono < deuda.total
        LEFT JOIN deuda.cuotas cuota WITH cuota.fechaVencimiento <= :fecha
        LEFT JOIN cuota.detalleFactura detalle   
        LEFT JOIN Contrato.plan plan
        LEFT JOIN Contrato.estadoActual est
        WHERE (Contrato.numero LIKE :param OR cli.nombres LIKE :param OR dni.numero LIKE :param)
        ORDER BY f.anioPago DESC,f.mesPago DESC, cuota.id DESC")
        ->setParameter(
            'param', "%$value%")
        ->setParameter('fecha', $fecha);

        $data = $query->getResult();
        return $data;
       
    }
    public function findByEstado(OpcionCatalogo $estado)
    {
        //$estadoCortado = $this->opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::CORTADO, 'est-cont');
        /* @var $query Query */
        $query = $this->createQueryBuilder('c')
            ->select('c, cli, dni, estadoActual, f')
            ->innerJoin('c.plan', 'p')
            ->leftJoin('c.facturas', 'f')
            ->innerJoin('c.cliente', 'cli')
            ->innerJoin('cli.dni', 'dni')
            ->leftJoin('c.estadoActual', 'estadoActual')
            ->leftJoin('estadoActual.catalogo', 'catalogo')
            ->where('c.estadoActual = :estado')
            ->orderBy('c.version', 'ASC')
            ->setParameter('estado', $estado)
            ->getQuery();
        $em = $this->getEntityManager();

        $data = $query->getResult();
        return $data;

    }

    public function findByNumero(string $numero)
    {
        //$estadoCortado = $this->opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::CORTADO, 'est-cont');
        /* @var $query Query */
        $query = $this->createQueryBuilder('c')
            ->select('c, cli, dni, estadoActual, f')
            ->innerJoin('c.plan', 'p')
            ->leftJoin('c.facturas', 'f')
            ->innerJoin('c.cliente', 'cli')
            ->innerJoin('cli.dni', 'dni')
            ->leftJoin('c.estadoActual', 'estadoActual')
            ->leftJoin('estadoActual.catalogo', 'catalogo')
            ->where('c.numero = :numero')
            ->orderBy('c.version', 'ASC')
            ->setParameter('numero', $numero)
            ->getQuery()
        ;


        $data = $query->getOneOrNullResult();
        return $data;

    }

    public function findAllRegisters()
    {
        /*
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
        */
        /* @var $query Query */
        $query = $this->createQueryBuilder('c')
            ->select('c, cli, dni, estadoActual')
            ->innerJoin('c.cliente', 'cli')
            ->innerJoin('cli.dni', 'dni')
            ->leftJoin('c.estadoActual', 'estadoActual')
            ->leftJoin('estadoActual.catalogo', 'catalogo')
            ->orderBy('c.version', 'ASC')
            ->getQuery();
        $em = $this->getEntityManager();

        $data = $query->getResult();
        return $data;

    }
    public function generarActivacion($anio, $mes){
        $em = $this->getEntityManager();
        $opcionRepository = $em->getRepository('App\Entity\OpcionCatalogo');
        $activo = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');

        $sql = "UPDATE contrato c
                    LEFT JOIN factura f ON c.id = f.contrato_id AND f.anio_pago = :anio AND f.mes_pago >= :mes AND (f.estado_sri != 'ANULADA' OR f.estado_sri IS NULL) AND f.factura_plan > 0
                    SET c.estado_actual_id = IF((f.id IS NOT NULL), :estado_activo ,c.estado_actual_id)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindParam('anio',$anio);
        $stmt->bindParam('mes', $mes);
        $stmt->bindParam('estado_activo', $activo->getId());
        return $stmt->executeStatement();
    }
    public function generarCorte($anio, $mes)
    {
        $em = $this->getEntityManager();
        $opcionRepository = $em->getRepository('App\Entity\OpcionCatalogo');
        /* @var $opcion OpcionCatalogo */
        $cortado = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::CORTADO, 'est-cont');
        $activo = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');
        $idActivo = $activo->getId();
        $idCortado = $cortado->getId();

        $sql = "UPDATE contrato c 
                LEFT JOIN factura f ON c.id = f.contrato_id AND f.anio_pago = :anio AND f.mes_pago >= :mes AND (f.estado_sri != 'ANULADA' OR f.estado_sri IS NULL)  AND f.factura_plan > 0
                    SET c.estado_actual_id = IF((f.id IS NULL AND (c.estado_actual_id = :estado_activo OR c.estado_actual_id IS NULL)), :estado_id, c.estado_actual_id);";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindParam('anio',$anio);
        $stmt->bindParam('mes', $mes);
        $stmt->bindParam('estado_activo', $idActivo);
        $stmt->bindParam('estado_id', $idCortado);
        return $stmt->executeStatement();
    }

    public function generarSuspendidos($anio, $mes)
    {
        $em = $this->getEntityManager();
        $opcionRepository = $em->getRepository('App\Entity\OpcionCatalogo');
        /* @var $opcion OpcionCatalogo */
        $cortado = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::CORTADO, 'est-cont');
        $activo = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');
        $suspendido = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::SUSPENDIDO, 'est-cont');

        $idActivo = $activo->getId();
        $idCortado = $cortado->getId();
        $sql = "UPDATE  contrato c 
                LEFT JOIN factura f ON c.id = f.contrato_id  
                SET c.meses_mora = 
                IF(
                    f.anio_pago < :anio, (:anio - f.anio_pago)*12 - f.mes_pago + :mes, :mes - f.mes_pago
                )
                WHERE (f.estado_sri != 'ANULADA' OR f.estado_sri IS NULL) AND f.id IS NOT NULL AND ( f.anio_pago < :anio OR f.mes_pago < :mes);";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindParam('anio',$anio);
        $stmt->bindParam('mes',     $mes);

        return $stmt->executeStatement();
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
    public function cargaMasiva($anio, $mes){
        $em = $this->getEntityManager();
        $opcionRepository = $em->getRepository('App\Entity\OpcionCatalogo');
        $activo = $opcionRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');

        $sql = "INSERT INTO contrato c (pppoe, vlan, nodo, numero, cliente_id, servicio_id)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindParam('anio',$anio);
        $stmt->bindParam('mes', $mes);
        $stmt->bindParam('estado_activo', $activo->getId());
        return $stmt->executeStatement();
    }

    public function actualizarMesesMora($anio, $mes){
        $em = $this->getEntityManager();

        $sql = "UPDATE  contrato c 
            LEFT JOIN factura f ON c.id = f.contrato_id  
            SET c.meses_mora = 
            IF(
                f.anio_pago < :anio, (:anio - f.anio_pago)*12 - f.mes_pago + :mes, :mes - f.mes_pago
            )
            WHERE (f.estado_sri != 'ANULADA' OR f.estado_sri IS NULL) AND f.id IS NOT NULL AND ( f.anio_pago < :anio OR f.mes_pago < :mes);";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindParam('anio',$anio);
        $stmt->bindParam('mes',     $mes);

        return $stmt->executeStatement();
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
