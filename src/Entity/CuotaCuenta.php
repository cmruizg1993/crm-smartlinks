<?php

namespace App\Entity;

use App\Repository\CuotaCuentaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CuotaCuentaRepository::class)
 */
class CuotaCuenta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaVencimiento;

    /**
     * @ORM\ManyToOne(targetEntity=CuentaPorCobrar::class, inversedBy="cuotas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cuenta;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valor;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $recargo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\OneToOne(targetEntity=DetalleFactura::class, mappedBy="cuota", cascade={"persist", "remove"})
     */
    private $detalleFactura;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getId()."";
    }

    public function getFechaVencimiento(): ?\DateTimeInterface
    {
        return $this->fechaVencimiento;
    }

    public function setFechaVencimiento(\DateTimeInterface $fechaVencimiento): self
    {
        $this->fechaVencimiento = $fechaVencimiento;

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

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getRecargo(): ?string
    {
        return $this->recargo;
    }

    public function setRecargo(?string $recargo): self
    {
        $this->recargo = $recargo;

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

    public function getDetalleFactura(): ?DetalleFactura
    {
        return $this->detalleFactura;
    }

    public function setDetalleFactura(?DetalleFactura $detalleFactura): self
    {
        // unset the owning side of the relation if necessary
        if ($detalleFactura === null && $this->detalleFactura !== null) {
            $this->detalleFactura->setCuota(null);
        }

        // set the owning side of the relation if necessary
        if ($detalleFactura !== null && $detalleFactura->getCuota() !== $this) {
            $detalleFactura->setCuota($this);
        }

        $this->detalleFactura = $detalleFactura;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }
    public function getPagada(){
        return $this->getDetalleFactura() ? true:false;
    }
}
