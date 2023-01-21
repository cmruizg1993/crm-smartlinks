<?php

namespace App\Entity;

use App\Repository\DetalleFacturaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetalleFacturaRepository::class)
 */
class DetalleFactura
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $cantidad;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $precio;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $subtotal;

    /**
     * @ORM\ManyToOne(targetEntity=Factura::class, inversedBy="detalles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $factura;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $esServicio;

    /**
     * @ORM\ManyToOne(targetEntity=Servicio::class, cascade={"persist"})
     */
    private $servicio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class)
     */
    private $producto;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $descuento;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $iva;

    /**
     * @ORM\OneToOne(targetEntity=CuotaCuenta::class, inversedBy="detalleFactura", cascade={"persist", "remove"})
     */
    private $cuota;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoPorcentaje;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $precioSinImp;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $precioConDesc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?string
    {
        return $this->cantidad;
    }

    public function setCantidad(string $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

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

    public function getFactura(): ?Factura
    {
        return $this->factura;
    }

    public function setFactura(?Factura $factura): self
    {
        $this->factura = $factura;

        return $this;
    }

    public function getEsServicio(): ?bool
    {
        return $this->esServicio;
    }

    public function setEsServicio(?bool $esServicio): self
    {
        $this->esServicio = $esServicio;

        return $this;
    }

    public function getServicio(): ?Servicio
    {
        return $this->servicio;
    }

    public function setServicio(?Servicio $servicio): self
    {
        $this->servicio = $servicio;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

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
    public function getIva(): ?string
    {
        return $this->iva;
    }

    public function setIva(?string $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getCuota(): ?CuotaCuenta
    {
        return $this->cuota;
    }

    public function setCuota(?CuotaCuenta $cuota): self
    {
        $this->cuota = $cuota;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getCodigoPorcentaje(): ?int
    {
        return $this->codigoPorcentaje;
    }

    public function setCodigoPorcentaje(?int $codigoPorcentaje): self
    {
        $this->codigoPorcentaje = $codigoPorcentaje;

        return $this;
    }

    public function getPrecioSinImp(): ?string
    {
        return $this->precioSinImp;
    }

    public function setPrecioSinImp(?string $precioSinImp): self
    {
        $this->precioSinImp = $precioSinImp;

        return $this;
    }

    public function getPrecioConDesc(): ?string
    {
        return $this->precioConDesc;
    }

    public function setPrecioConDesc(string $precioConDesc): self
    {
        $this->precioConDesc = $precioConDesc;

        return $this;
    }
}
