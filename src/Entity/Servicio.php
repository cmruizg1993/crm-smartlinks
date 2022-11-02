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
    const CODIGO_RECONEXION = 'S015';
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
    private $activo = true;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $precio;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="plan")
     */
    private $solicitudes;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $codigo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoPorcentaje;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $incluyeIva = true;

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
    public function setId($id):self
    {
        $this->id = $id;
        return $this;
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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): self
    {
        $this->precio = $precio;

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

    public function getCodigoPorcentaje(): ?int
    {
        return $this->codigoPorcentaje;
    }

    public function setCodigoPorcentaje(?OpcionCatalogo $codigoPorcentaje): self
    {
        $this->codigoPorcentaje = $codigoPorcentaje ? $codigoPorcentaje->getCodigo(): null;

        return $this;
    }

    public function getIncluyeIva(): ?bool
    {
        return $this->incluyeIva;
    }

    public function setIncluyeIva(?bool $incluyeIva): self
    {
        $this->incluyeIva = $incluyeIva;

        return $this;
    }
}
