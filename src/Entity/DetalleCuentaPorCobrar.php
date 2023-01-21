<?php

namespace App\Entity;

use App\Repository\DetalleCuentaPorCobrarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetalleCuentaPorCobrarRepository::class)
 */
class DetalleCuentaPorCobrar
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
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $subtotal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esServicio;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $iva;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class)
     */
    private $producto;

    /**
     * @ORM\ManyToOne(targetEntity=Servicio::class)
     */
    private $servicio;

    /**
     * @ORM\ManyToOne(targetEntity=CuentaPorCobrar::class, inversedBy="detalles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cuenta;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $precioSinImp;

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


    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function isEsServicio(): ?bool
    {
        return $this->esServicio;
    }

    public function setEsServicio(bool $esServicio): self
    {
        $this->esServicio = $esServicio;

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

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

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

    public function getCuenta(): ?CuentaPorCobrar
    {
        return $this->cuenta;
    }

    public function setCuenta(?CuentaPorCobrar $cuenta): self
    {
        $this->cuenta = $cuenta;

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
}
