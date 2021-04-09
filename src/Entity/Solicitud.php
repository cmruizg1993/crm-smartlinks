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
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="solicitudes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToMany(targetEntity=Plan::class, inversedBy="solicitudes")
     */
    private $planes;

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="solicitudes")
     */
    private $vendedor;

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

    /**
     * @return Collection|Plan[]
     */
    public function getPlanes(): Collection
    {
        return $this->planes;
    }

    public function addPlane(Plan $plane): self
    {
        if (!$this->planes->contains($plane)) {
            $this->planes[] = $plane;
        }

        return $this;
    }

    public function removePlane(Plan $plane): self
    {
        $this->planes->removeElement($plane);

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
}
