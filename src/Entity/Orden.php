<?php

namespace App\Entity;

use App\Repository\OrdenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdenRepository::class)
 */
class Orden
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TipoOrden::class, inversedBy="ordenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipo;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=SAN::class, inversedBy="ordenes",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $san;

    /**
     * @ORM\ManyToOne(targetEntity=EstadoOrden::class, inversedBy="ordenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $estado;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="ordenes",cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $tecnico;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigo;

    /**
     * @ORM\OneToMany(targetEntity=Evento::class, mappedBy="orden",cascade={"persist", "remove"})
     */
    private $eventos;

    public function __construct()
    {
        $this->eventos = new ArrayCollection();
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?TipoOrden
    {
        return $this->tipo;
    }

    public function setTipo(?TipoOrden $tipo): self
    {
        $this->tipo = $tipo;

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

    public function getSan(): ?SAN
    {
        return $this->san;
    }

    public function setSan(?SAN $san): self
    {
        $this->san = $san;

        return $this;
    }

    public function getEstado(): ?EstadoOrden
    {
        return $this->estado;
    }

    public function setEstado(?EstadoOrden $estado): self
    {
        $this->estado = $estado;

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

    public function getTecnico(): ?Colaborador
    {
        return $this->tecnico;
    }

    public function setTecnico(?Colaborador $tecnico): self
    {
        $this->tecnico = $tecnico;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return Collection|Evento[]
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos[] = $evento;
            $evento->setOrden($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        if ($this->eventos->removeElement($evento)) {
            // set the owning side to null (unless already changed)
            if ($evento->getOrden() === $this) {
                $evento->setOrden(null);
            }
        }

        return $this;
    }

}
