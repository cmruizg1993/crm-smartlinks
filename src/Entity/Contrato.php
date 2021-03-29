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
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="contratos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;



    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="contratos")
     */
    private $instalador;

    /**
     * @ORM\ManyToMany(targetEntity=PlanesClaro::class, inversedBy="contratos")
     */
    private $planes;

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
     * @return Collection|PlanesClaro[]
     */
    public function getPlanes(): Collection
    {
        return $this->planes;
    }

    public function addPlane(PlanesClaro $plane): self
    {
        if (!$this->planes->contains($plane)) {
            $this->planes[] = $plane;
        }

        return $this;
    }

    public function removePlane(PlanesClaro $plane): self
    {
        $this->planes->removeElement($plane);

        return $this;
    }

}
