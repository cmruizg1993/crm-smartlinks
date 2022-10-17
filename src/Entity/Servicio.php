<?php

namespace App\Entity;

use App\Repository\ServicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServicioRepository::class)
 */
class Servicio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;


    /**
     * @ORM\OneToMany(targetEntity=Contrato::class, mappedBy="plan", cascade={"persist", "remove"})
     */
    private $Contratos;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costo;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="plan")
     */
    private $solicitudes;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $codigo;

    public function __toString()
    {
        return $this->getNombre();
    }
    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->contratos = new ArrayCollection();
        $this->Contratos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    /**
     * @return Collection|Contrato[]
     */
    public function getContratos(): Collection
    {
        return $this->Contratos;
    }

    public function addContrato(Contrato $Contrato): self
    {
        if (!$this->Contratos->contains($Contrato)) {
            $this->Contratos[] = $Contrato;
            $Contrato->setPlan($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $Contrato): self
    {
        if ($this->Contratos->removeElement($Contrato)) {
            // set the owning side to null (unless already changed)
            if ($Contrato->getPlan() === $this) {
                $Contrato->setPlan(null);
            }
        }

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getCosto(): ?float
    {
        return $this->costo;
    }

    public function setCosto(?float $costo): self
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): self
    {
        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes[] = $solicitude;
            $solicitude->setPlan($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getPlan() === $this) {
                $solicitude->setPlan(null);
            }
        }

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }
}
