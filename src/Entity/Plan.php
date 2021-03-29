<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan
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
     * @ORM\ManyToOne(targetEntity=Servicio::class, inversedBy="planes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $servicio;

    /**
     * @ORM\ManyToMany(targetEntity=Solicitud::class, mappedBy="planes")
     */
    private $solicitudes;

    /**
     * @ORM\OneToMany(targetEntity=SAN::class, mappedBy="plan")
     */
    private $sans;

    public function __toString()
    {
        return $this->getNombre();
    }
    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->contratos = new ArrayCollection();
        $this->sans = new ArrayCollection();
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
            $solicitude->addPlane($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            $solicitude->removePlane($this);
        }

        return $this;
    }

    /**
     * @return Collection|SAN[]
     */
    public function getSans(): Collection
    {
        return $this->sans;
    }

    public function addSan(SAN $san): self
    {
        if (!$this->sans->contains($san)) {
            $this->sans[] = $san;
            $san->setPlan($this);
        }

        return $this;
    }

    public function removeSan(SAN $san): self
    {
        if ($this->sans->removeElement($san)) {
            // set the owning side to null (unless already changed)
            if ($san->getPlan() === $this) {
                $san->setPlan(null);
            }
        }

        return $this;
    }
}
