<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaRepository::class)
 */
class Factura
{
    const NOMBRE_CATALOGO = 'tipo-comp';
    const FACTURA = '01';
    const NOTA_VENTA = '00';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     */
    private $total;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $subtotal;


    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $iva;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $baseIce;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $ice;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $baseCero;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $baseNoImponible;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @var $serial string
     */
    private $serial;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $secuencial;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referencia;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\OneToMany(targetEntity=DetalleFactura::class, mappedBy="factura", cascade={"persist"})
     */
    private $detalles;

    /**
     * @var $tipoComprobante string
     */
    private $tipoComprobante;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $tipoAmbiente;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoNumerico;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $tipoEmision = '1';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $digitoVerificador;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $formaPago;

    /**
     * @ORM\ManyToOne(targetEntity=Contrato::class, inversedBy="facturas")
     */
    private $contrato;

    /**
     * @ORM\ManyToOne(targetEntity=PuntoEmision::class, inversedBy="facturas")
     */
    private $puntoEmision;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mesPago;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anioPago;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $comprobantePago;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $subtotal12;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $subtotal0;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $descuento;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $propina = 0;

    public $ruc;

    /**
     * @ORM\Column(type="string", length=49, nullable=true)
     */
    private $claveAcceso;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $estadoSri;

    public function __construct()
    {
        $this->detalles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubtotal(): ?string
    {
        return $this->subtotal;
    }

    public function setSubtotal(string $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getBaseIva(): ?string
    {
        return $this->baseIva;
    }

    public function setBaseIva(string $baseIva): self
    {
        $this->baseIva = $baseIva;

        return $this;
    }

    public function getIva(): ?string
    {
        return $this->iva;
    }

    public function setIva(string $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getBaseIce(): ?string
    {
        return $this->baseIce;
    }

    public function setBaseIce(?string $baseIce): self
    {
        $this->baseIce = $baseIce;

        return $this;
    }

    public function getIce(): ?string
    {
        return $this->ice;
    }

    public function setIce(?string $ice): self
    {
        $this->ice = $ice;

        return $this;
    }

    public function getBaseCero(): ?string
    {
        return $this->baseCero;
    }

    public function setBaseCero(?string $baseCero): self
    {
        $this->baseCero = $baseCero;

        return $this;
    }

    public function getBaseNoImponible(): ?string
    {
        return $this->baseNoImponible;
    }

    public function setBaseNoImponible(?string $baseNoImponible): self
    {
        $this->baseNoImponible = $baseNoImponible;

        return $this;
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

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getSecuencial(): ?string
    {
        $secuencial = $this->secuencial;
        return $secuencial;
    }

    public function setSecuencial(string $secuencial): self
    {
        $this->secuencial = $secuencial;

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

    public function getReferencia(): ?string
    {
        return $this->referencia;
    }

    public function setReferencia(?string $referencia): self
    {
        $this->referencia = $referencia;

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

    /**
     * @return Collection|DetalleFactura[]
     */
    public function getDetalles(): Collection
    {
        return $this->detalles;
    }

    public function addDetalle(DetalleFactura $detalle): self
    {
        if (!$this->detalles->contains($detalle)) {
            $this->detalles[] = $detalle;
            $detalle->setFactura($this);
        }

        return $this;
    }

    public function removeDetalle(DetalleFactura $detalle): self
    {
        if ($this->detalles->removeElement($detalle)) {
            // set the owning side to null (unless already changed)
            if ($detalle->getFactura() === $this) {
                $detalle->setFactura(null);
            }
        }

        return $this;
    }

    public function getTipoComprobante(): ?string
    {
        return $this->getPuntoEmision()->getTipoComprobante()->getCodigo();
    }

    public function setTipoComprobante(?string $tipoComprobante): self
    {
        $this->tipoComprobante = $tipoComprobante;

        return $this;
    }

    public function getTipoAmbiente(): ?string
    {
        return $this->tipoAmbiente;
    }

    public function setTipoAmbiente(?string $tipoAmbiente): self
    {
        $this->tipoAmbiente = $tipoAmbiente;

        return $this;
    }

    public function getCodigoNumerico(): ?int
    {
        return $this->codigoNumerico;
    }

    public function setCodigoNumerico(?int $codigoNumerico): self
    {
        $this->codigoNumerico = $codigoNumerico;

        return $this;
    }

    public function getTipoEmision(): ?string
    {
        return $this->tipoEmision;
    }

    public function setTipoEmision(?string $tipoEmision): self
    {
        $this->tipoEmision = $tipoEmision;

        return $this;
    }

    public function getDigitoVerificador(): ?int
    {
        return $this->digitoVerificador;
    }

    public function setDigitoVerificador(?int $digitoVerificador): self
    {
        $this->digitoVerificador = $digitoVerificador;

        return $this;
    }
    public function generarClaveAcceso(){
        $claveAcceso = '';
        $claveAcceso .= $this->getFecha()->format('dmY');

            $n = rand(10000000, 99999999);
            $digito8 = $n.'';
            $estab = $this->getPuntoEmision()->getEstablecimiento()->getCodigo();
            $ptoEmi = $this->getPuntoEmision()->getCodigo();
            $claveAcceso .= "$this->tipoComprobante$this->ruc$this->tipoAmbiente$estab$ptoEmi$this->secuencial$digito8$this->tipoEmision";

            $suma = 0;
            $factor = 7;
            foreach(str_split($claveAcceso) as $item ){
                $suma = $suma + (int)$item * $factor;
                $factor = $factor - 1;
                if ($factor == 1)$factor = 7;
            }

            $digitoVerificador = ($suma % 11);
            $digitoVerificador = 11 - $digitoVerificador;

            if ($digitoVerificador == 11)$digitoVerificador = 0;
            if ($digitoVerificador == 10)$digitoVerificador = 1;

            $claveAcceso.=$digitoVerificador;
            $this->claveAcceso = $claveAcceso;
            return $claveAcceso;
    }

    public function getFormaPago(): ?string
    {
        return $this->formaPago;
    }

    public function setFormaPago(?string $formaPago): self
    {
        $this->formaPago = $formaPago;

        return $this;
    }

    public function getContrato(): ?Contrato
    {
        return $this->contrato;
    }

    public function setContrato(?Contrato $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }

    public function getPuntoEmision(): ?PuntoEmision
    {
        return $this->puntoEmision;
    }

    public function setPuntoEmision(?PuntoEmision $puntoEmision): self
    {
        $this->puntoEmision = $puntoEmision;

        return $this;
    }

    public function getMesPago(): ?int
    {
        return $this->mesPago;
    }

    public function setMesPago(?int $mesPago): self
    {
        $this->mesPago = $mesPago;

        return $this;
    }

    public function getAnioPago(): ?int
    {
        return $this->anioPago;
    }

    public function setAnioPago(?int $anioPago): self
    {
        $this->anioPago = $anioPago;

        return $this;
    }

    public function getComprobantePago(): ?string
    {
        return $this->comprobantePago;
    }

    public function setComprobantePago(?string $comprobantePago): self
    {
        $this->comprobantePago = $comprobantePago;

        return $this;
    }

    public function getSubtotal12(): ?string
    {
        return $this->subtotal12;
    }

    public function setSubtotal12(?string $subtotal12): self
    {
        $this->subtotal12 = $subtotal12;

        return $this;
    }

    public function getSubtotal0(): ?string
    {
        return $this->subtotal0;
    }

    public function setSubtotal0(string $subtotal0): self
    {
        $this->subtotal0 = $subtotal0;

        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $descuento): self
    {
        $this->descuento = $descuento;

        return $this;
    }

    public function getPropina(): ?string
    {
        return $this->propina;
    }

    public function setPropina(?string $propina): self
    {
        $this->propina = $propina;

        return $this;
    }

    public function getClaveAcceso(): ?string
    {
        return $this->claveAcceso;
    }

    public function setClaveAcceso(?string $claveAcceso): self
    {
        $this->claveAcceso = $claveAcceso;

        return $this;
    }

    public function getEstadoSri(): ?string
    {
        return $this->estadoSri;
    }

    public function setEstadoSri(?string $estadoSri): self
    {
        $this->estadoSri = $estadoSri;

        return $this;
    }
    public function getNombres(){
        return $this->cliente->getNombres();
    }
    public function getCedula(){
        return $this->cliente->getDni()->getNumero();
    }
    public function getNumero(){
        return $this->contrato->getNumero();
    }

}
