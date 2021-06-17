<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     *
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
     * @Assert\NotBlank(message="Elija una forma de pago")
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
     * @Assert\NotBlank(message="Seleccione un plan")
     * @ORM\ManyToOne(targetEntity=Plan::class, inversedBy="solicitudes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $estado;

    /**
     * @Assert\NotBlank(message="Selleccione una ubicación")
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @Assert\NotBlank(message="Selleccione una ubicación")
     * @ORM\Column(type="float")
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $aprobacionEquifax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validacionEquifax;

    /**
     * @ORM\OneToOne(targetEntity=SAN::class, cascade={"persist", "remove"}, inversedBy="solicitud")
     */
    private $san;

    /**
     * @ORM\OneToOne(targetEntity=Pago::class, cascade={"persist", "remove"})
     */
    private $pago;



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

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getAprobacionEquifax(): ?string
    {
        return $this->aprobacionEquifax;
    }

    public function setAprobacionEquifax(?string $aprobacionEquifax): self
    {
        $this->aprobacionEquifax = $aprobacionEquifax;

        return $this;
    }

    public function getValidacionEquifax(): ?string
    {
        return $this->validacionEquifax;
    }

    public function setValidacionEquifax(?string $validacionEquifax): self
    {
        $this->validacionEquifax = $validacionEquifax;

        return $this;
    }

    public function getSan(): ?SAN
    {
        return $this->san;
    }

    public function setSan(?SAN $san): self
    {
        $this->san = $san;

        return $this;
    }

    public function getPago(): ?Pago
    {
        return $this->pago;
    }

    public function setPago(?Pago $pago): self
    {
        $this->pago = $pago;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validarFormaPago(ExecutionContextInterface $context, $payload){
        if($this->getFormaPago()){
            if($this->getFormaPago()->getCodigo()!='EF'){
                if(!$this->getCuentaBancaria()){
                    $context->addViolation('Debe seleccionar una cta Bancaria');
                }

            }
        }else{
            $context->addViolation('Debe seleccionar una forma de pago');
        }
    }
}
