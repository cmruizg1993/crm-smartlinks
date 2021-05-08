<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SolicitudRepository::class)
 */
class Solicitud
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="solicitudes",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="solicitudes")
     */
    private $vendedor;

    /**
     * @ORM\ManyToOne(targetEntity=FormaPago::class)
     */
    private $formaPago;

    /**
     * @ORM\ManyToOne(targetEntity=CuentaBancaria::class, inversedBy="solicitudes")
     */
    private $cuentaBancaria;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aprobar=false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $capturaEquifax;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class, inversedBy="solicitudes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $estado;



    public function __construct()
    {
        $this->planes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getVendedor(): ?Colaborador
    {
        return $this->vendedor;
    }

    public function setVendedor(?Colaborador $vendedor): self
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    public function getFormaPago(): ?FormaPago
    {
        return $this->formaPago;
    }

    public function setFormaPago(?FormaPago $formaPago): self
    {
        $this->formaPago = $formaPago;

        return $this;
    }

    public function getCuentaBancaria(): ?CuentaBancaria
    {
        return $this->cuentaBancaria;
    }

    public function setCuentaBancaria(?CuentaBancaria $cuentaBancaria): self
    {
        $this->cuentaBancaria = $cuentaBancaria;

        return $this;
    }

    public function getAprobar(): ?bool
    {
        return $this->aprobar;
    }

    public function setAprobar(?bool $aprobar): self
    {
        $this->aprobar = $aprobar;

        return $this;
    }

    public function getCapturaEquifax(): ?string
    {
        return $this->capturaEquifax;
    }

    public function setCapturaEquifax(?string $capturaEquifax): self
    {
        $this->capturaEquifax = $capturaEquifax;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

}
