<?php

namespace App\Entity;

use App\Repository\TipoOrdenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoOrdenRepository::class)
 */
class TipoOrden
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
     * @ORM\Column(type="string", length=1, unique=true)
     */
    private $codigo;

    /**
     * @ORM\OneToMany(targetEntity=Orden::class, mappedBy="tipo")
     */
    private $ordenes;

    /**
     * @ORM\OneToMany(targetEntity=Comision::class, mappedBy="orden")
     */
    private $comisiones;
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->nombre;
    }

    public function __construct()
    {
        $this->ordenes = new ArrayCollection();
        $this->comisiones = new ArrayCollection();
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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

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
            $ordene->setTipo($this);
        }

        return $this;
    }

    public function removeOrdene(Orden $ordene): self
    {
        if ($this->ordenes->removeElement($ordene)) {
            // set the owning side to null (unless already changed)
            if ($ordene->getTipo() === $this) {
                $ordene->setTipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comision[]
     */
    public function getComisiones(): Collection
    {
        return $this->comisiones;
    }

    public function addComisione(Comision $comisione): self
    {
        if (!$this->comisiones->contains($comisione)) {
            $this->comisiones[] = $comisione;
            $comisione->setOrden($this);
        }

        return $this;
    }

    public function removeComisione(Comision $comisione): self
    {
        if ($this->comisiones->removeElement($comisione)) {
            // set the owning side to null (unless already changed)
            if ($comisione->getOrden() === $this) {
                $comisione->setOrden(null);
            }
        }

        return $this;
    }
}
