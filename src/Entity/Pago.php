<?php

namespace App\Entity;

use App\Repository\PagoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PagoRepository::class)
 */
class Pago
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $valor;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nroDocumento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comprobante;

    /**
     * @ORM\ManyToOne(targetEntity=CuentaBancaria::class, inversedBy="pagos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ctaBancaria;

    /**
     * @ORM\ManyToOne(targetEntity=CuentaPorCobrar::class, inversedBy="pagos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cuentaPorCobrar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getNroDocumento(): ?string
    {
        return $this->nroDocumento;
    }

    public function setNroDocumento(string $nroDocumento): self
    {
        $this->nroDocumento = $nroDocumento;

        return $this;
    }

    public function getComprobante(): ?string
    {
        return $this->comprobante;
    }

    public function setComprobante(?string $comprobante): self
    {
        $this->comprobante = $comprobante;

        return $this;
    }

    public function getCtaBancaria(): ?CuentaBancaria
    {
        return $this->ctaBancaria;
    }

    public function setCtaBancaria(?CuentaBancaria $ctaBancaria): self
    {
        $this->ctaBancaria = $ctaBancaria;

        return $this;
    }

    public function getCuentaPorCobrar(): ?CuentaPorCobrar
    {
        return $this->cuentaPorCobrar;
    }

    public function setCuentaPorCobrar(?CuentaPorCobrar $cuentaPorCobrar): self
    {
        $this->cuentaPorCobrar = $cuentaPorCobrar;

        return $this;
    }
}
