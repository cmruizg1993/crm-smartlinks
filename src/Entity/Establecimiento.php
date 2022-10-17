<?php

namespace App\Entity;

use App\Repository\EstablecimientoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstablecimientoRepository::class)
 */
class Establecimiento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity=PuntoEmision::class, mappedBy="establecimiento", orphanRemoval=true)
     */
    private $puntosEmision;

    public function __construct()
    {
        $this->puntosEmision = new ArrayCollection();
    }
    public function __toString(){
        return $this->getNombre() .' - '. $this->getCodigo();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return Collection|PuntoEmision[]
     */
    public function getPuntosEmision(): Collection
    {
        return $this->puntosEmision;
    }

    public function addPuntosEmision(PuntoEmision $puntosEmision): self
    {
        if (!$this->puntosEmision->contains($puntosEmision)) {
            $this->puntosEmision[] = $puntosEmision;
            $puntosEmision->setEstablecimiento($this);
        }

        return $this;
    }

    public function removePuntosEmision(PuntoEmision $puntosEmision): self
    {
        if ($this->puntosEmision->removeElement($puntosEmision)) {
            // set the owning side to null (unless already changed)
            if ($puntosEmision->getEstablecimiento() === $this) {
                $puntosEmision->setEstablecimiento(null);
            }
        }

        return $this;
    }
}
