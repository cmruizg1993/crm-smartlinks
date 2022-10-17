<?php

namespace App\Entity;

use App\Repository\ContratoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratoRepository::class)
 */
class Contrato
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
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="Contratos",cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity=Servicio::class, inversedBy="Contratos", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, name="servicio_id")
     */
    private $plan;


    /**
     * @ORM\ManyToOne(targetEntity=Parroquia::class, inversedBy="Contratos")
     */
    private $parroquia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="Contratos")
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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valorSuscripcion;
    /**
     * @ORM\OneToOne(targetEntity=Solicitud::class, inversedBy="Contrato",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
    private $solicitud;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vlan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nodo;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $nap;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $puerto;

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class)
     */
    private $instalador;

    /**
     * @ORM\OneToMany(targetEntity=EquipoInstalacion::class, mappedBy="contrato", cascade={"persist", "remove"})
     */
    private $equipos;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->numero;
    }

    public function __construct()
    {
        //$this->ordenes = new ArrayCollection();
        $this->equipos = new ArrayCollection();
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

    public function getPlan(): ?Servicio
    {
        return $this->plan;
    }

    public function setPlan(?Servicio $plan): self
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
            $ordene->setContrato($this);
        }

        return $this;
    }

    public function removeOrdene(Orden $ordene): self
    {
        if ($this->ordenes->removeElement($ordene)) {
            // set the owning side to null (unless already changed)
            if ($ordene->getContrato() === $this) {
                $ordene->setContrato(null);
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

    public function getValorSuscripcion(): ?float
    {
        return $this->valorSuscripcion;
    }

    public function setValorSuscripcion(?float $valorSuscripcion): self
    {
        $this->valorSuscripcion = $valorSuscripcion;

        return $this;
    }

    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    public function setSolicitud(?Solicitud $solicitud): self
    {
        // unset the owning side of the relation if necessary
        if ($solicitud === null && $this->solicitud !== null) {
            $this->solicitud->setContrato(null);
        }

        // set the owning side of the relation if necessary
        if ($solicitud !== null && $solicitud->getContrato() !== $this) {
            $solicitud->setContrato($this);
        }

        $this->solicitud = $solicitud;

        return $this;
    }

    public function getVlan(): ?int
    {
        return $this->vlan;
    }

    public function setVlan(?int $vlan): self
    {
        $this->vlan = $vlan;

        return $this;
    }

    public function getNodo(): ?string
    {
        return $this->nodo;
    }

    public function setNodo(?string $nodo): self
    {
        $this->nodo = $nodo;

        return $this;
    }

    public function getNap(): ?string
    {
        return $this->nap;
    }

    public function setNap(?string $nap): self
    {
        $this->nap = $nap;

        return $this;
    }

    public function getPuerto(): ?string
    {
        return $this->puerto;
    }

    public function setPuerto(?string $puerto): self
    {
        $this->puerto = $puerto;

        return $this;
    }

    public function getInstalador(): ?Colaborador
    {
        return $this->instalador;
    }

    public function setInstalador(?Colaborador $instalador): self
    {
        $this->instalador = $instalador;

        return $this;
    }

    /**
     * @return Collection|EquipoInstalacion[]
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(EquipoInstalacion $equipo): self
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos[] = $equipo;
            $equipo->setContrato($this);
        }

        return $this;
    }

    public function removeEquipo(EquipoInstalacion $equipo): self
    {
        if ($this->equipos->removeElement($equipo)) {
            // set the owning side to null (unless already changed)
            if ($equipo->getContrato() === $this) {
                $equipo->setContrato(null);
            }
        }

        return $this;
    }
}
