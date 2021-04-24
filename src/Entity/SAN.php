<?php

namespace App\Entity;

use App\Repository\SANRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SANRepository::class)
 */
class SAN
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="sans",cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class, inversedBy="sans", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $plan;

    /**
     * @ORM\OneToMany(targetEntity=Orden::class, mappedBy="san", cascade={"persist", "remove"})
     */
    private $ordenes;

    /**
     * @ORM\ManyToOne(targetEntity=Parroquia::class, inversedBy="sans")
     */
    private $parroquia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="sans")
     */
    private $vendedor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $estadoContrato;
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->numero;
    }

    public function __construct()
    {
        $this->ordenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

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

    /**
     * @return Collection|Orden[]
     */
    public function getOrdenes(): Collection
    {
        return $this->ordenes;
    }

    public function addOrdene(Orden $ordene): self
    {
        if (!$this->ordenes->contains($ordene)) {
            $this->ordenes[] = $ordene;
            $ordene->setSan($this);
        }

        return $this;
    }

    public function removeOrdene(Orden $ordene): self
    {
        if ($this->ordenes->removeElement($ordene)) {
            // set the owning side to null (unless already changed)
            if ($ordene->getSan() === $this) {
                $ordene->setSan(null);
            }
        }

        return $this;
    }

    public function getParroquia(): ?Parroquia
    {
        return $this->parroquia;
    }

    public function setParroquia(?Parroquia $parroquia): self
    {
        $this->parroquia = $parroquia;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getEstadoContrato(): ?string
    {
        return $this->estadoContrato;
    }

    public function setEstadoContrato(?string $estadoContrato): self
    {
        $this->estadoContrato = $estadoContrato;

        return $this;
    }
}
