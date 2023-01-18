<?php

namespace App\Entity;

use App\Repository\CuentaPorCobrarRepository;
use App\Repository\OpcionCatalogoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Exception\InvalidArgumentException;

/**
[0:51 p. m., 4/1/2023] Cristian Ruiz😎: 1. Se crea la cuenta por cobrar como que fuera una factura, en su respectivo módulo
[0:52 p. m., 4/1/2023] Cristian Ruiz😎: 1.1 Se le define el contrato al que será atada la cxc
[0:53 p. m., 4/1/2023] Cristian Ruiz😎: 1.2 Se le define le agregan los servicios que serían diferidos
[0:53 p. m., 4/1/2023] Cristian Ruiz😎: 1.3 Se le define la fecha inicial de la cxc
[0:55 p. m., 4/1/2023] Cristian Ruiz😎: 1.4 El sistema genera las cuotas a partir de la fechas y el total de la cxc (cada cuota tendrá una fecha de vencimiento)
[0:55 p. m., 4/1/2023] Cristian Ruiz😎: 1.5 El usuario deberá guardar la cxc
[0:57 p. m., 4/1/2023] Cristian Ruiz😎: 2. Al ingresar al módulo de facturación y cargar el contrato al que se le va a facturar, paralelamente, se traerán las CUOTAS con FECHA VENCIDA del contrato agregado
[1:00 p. m., 4/1/2023] Cristian Ruiz😎: 3. En un proceso  SIN CUOTAS VENCIDAS te permitirá guardar de manera normal, pero si EL CONTRATO trae CUOTAS VENCIDAS, el sistema te lanzara una ventana de ALERTA ! , para que decidas si agregar las CUOTAS A LA FACTURA (esto desde la pestaña de CXC)
[1:02 p. m., 4/1/2023] Cristian Ruiz😎: Observación: Para agregar el detalle a la factura, se concatenará el nombre de cada servicio agregado al momento que se creo la CUENTA x COBRAR, y para el proceso de FE se le asignara el CXC-(ID DEL REGISTRO DE LA CUENTA)
[1:03 p. m., 4/1/2023] Cristian Ruiz😎: como código Principal y Auxiliar
[1:04 p. m., 4/1/2023] Cristian Ruiz😎: Observación 2: Se mantiene las formas de pago como se ha mantenido hasta el momento
 */
/**
 * @ORM\Entity(repositoryClass=CuentaPorCobrarRepository::class)
 */
class CuentaPorCobrar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\OneToMany(targetEntity=Pago::class, mappedBy="cuentaPorCobrar", orphanRemoval=true)
     */
    private $pagos;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="deudas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\Column(type="integer")
     */
    private $plazo;

    /**
     * @ORM\OneToMany(targetEntity=DetalleCuentaPorCobrar::class, mappedBy="cuenta", cascade={"persist"}, orphanRemoval=true)
     */
    private $detalles;

    /**
     * @ORM\OneToMany(targetEntity=CuotaCuenta::class, mappedBy="cuenta", cascade={"persist"}, orphanRemoval=true)
     */
    private $cuotas;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $abono;

    public function __construct()
    {
        $this->pagos = new ArrayCollection();
        $this->detalles = new ArrayCollection();
        $this->cuotas = new ArrayCollection();
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->id."";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * @return Collection<int, Pago>
     */
    public function getPagos(): Collection
    {
        return $this->pagos;
    }

    public function addPago(Pago $pago): self
    {
        if (!$this->pagos->contains($pago)) {
            $this->pagos[] = $pago;
            $pago->setCuentaPorCobrar($this);
        }

        return $this;
    }

    public function removePago(Pago $pago): self
    {
        if ($this->pagos->removeElement($pago)) {
            // set the owning side to null (unless already changed)
            if ($pago->getCuentaPorCobrar() === $this) {
                $pago->setCuentaPorCobrar(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getPlazo(): ?int
    {
        return $this->plazo;
    }

    public function setPlazo(int $plazo): self
    {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * @return Collection<int, DetalleCuentaPorCobrar>
     */
    public function getDetalles(): Collection
    {
        return $this->detalles;
    }

    public function addDetalle(DetalleCuentaPorCobrar $detalle): self
    {
        if (!$this->detalles->contains($detalle)) {
            $this->detalles[] = $detalle;
            $detalle->setCuenta($this);
        }

        return $this;
    }

    public function removeDetalle(DetalleCuentaPorCobrar $detalle): self
    {
        if ($this->detalles->removeElement($detalle)) {
            // set the owning side to null (unless already changed)
            if ($detalle->getCuenta() === $this) {
                $detalle->setCuenta(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CuotaCuenta>
     */
    public function getCuotas(): Collection
    {
        return $this->cuotas;
    }

    public function addCuota(CuotaCuenta $cuota): self
    {
        if (!$this->cuotas->contains($cuota)) {
            $this->cuotas[] = $cuota;
            $cuota->setCuenta($this);
        }

        return $this;
    }

    public function removeCuota(CuotaCuenta $cuota): self
    {
        if ($this->cuotas->removeElement($cuota)) {
            // set the owning side to null (unless already changed)
            if ($cuota->getCuenta() === $this) {
                $cuota->setCuenta(null);
            }
        }

        return $this;
    }

    public function totalizar(OpcionCatalogoRepository $opcionCatalogoRepository){
        /* AGREGANDO DETALLES Y CALCULANDO TOTALES */
        $total= 0;
        $subtotal = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotalNOI = 0;
        $iva = 0;
        $detalles = $this->getDetalles();

        /* @var $detalle DetalleCuentaPorCobrar */
        foreach ($detalles as $detalle){

            if($detalle->isEsServicio()){
                $servicio = $detalle->getServicio();
                /* @var $impuesto OpcionCatalogo */
                $impuesto = $opcionCatalogoRepository->findOneByCodigoyCatalogo($servicio->getCodigoPorcentaje(), 'iva');
                $detalle->codigoPorcentaje = $impuesto;
                if(!$servicio || !$servicio->getId()) throw new InvalidArgumentException();
                $totalDetalle = ($detalle->getCantidad() * $detalle->getPrecio());
                $porcentaje = $impuesto->getValorNumerico()/100;
                $ivaDetalle = null;
                $precioDetalle = $detalle->getPrecio();
                if($servicio->getIncluyeIva()){
                    $subtotalDetalle = $totalDetalle/(1 + $porcentaje);
                    $ivaDetalle = $totalDetalle - $subtotalDetalle;
                    $precioDetalle = $precioDetalle/(1 + $porcentaje);
                    $detalle->setPrecio($precioDetalle);
                }else{
                    $subtotalDetalle = $totalDetalle;
                    $totalDetalle = $subtotalDetalle*(1 + $porcentaje);
                    $ivaDetalle = $totalDetalle - $subtotalDetalle;
                }
                if($ivaDetalle > 0){
                    $subtotal12 += $subtotalDetalle;

                }else{
                    $subtotal0 += $subtotalDetalle;
                }
                $subtotal += $subtotalDetalle;
                $detalle->setSubtotal($subtotalDetalle);
                $detalle->setIva($ivaDetalle);
                $total += $totalDetalle;
                $iva += $ivaDetalle;
            }else{
                // por completar
            }
            $detalle->setCuenta($this);
        }
        $this->total =($total);
        /*
        $this->subtotal =($subtotal);
        $this->iva = ($iva);
        $this->subtotal0 = ($subtotal0);
        $this->subtotal12 = ($subtotal12);
        */
    }
    public function getSaldo(){
        $cuotas = $this->getCuotas();
        $abono = 0;
        /* @var $cuota CuotaCuenta */
        foreach ($cuotas as $cuota){
            if($cuota->getDetalleFactura()){
                $abono += $cuota->getValor();
            }
        }
        $saldo = $this->total - $abono;
        return $saldo;
    }

    public function getAbono(): ?string
    {
        return $this->abono;
    }

    public function setAbono(?string $abono): self
    {
        $this->abono = $abono;

        return $this;
    }
}
