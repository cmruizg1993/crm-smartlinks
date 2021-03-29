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
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity=Proveedor::class, inversedBy="servicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proveedor;

    /**
     * @ORM\OneToMany(targetEntity=Plan::class, mappedBy="servicio")
     */
    private $planes;

    public function __toString()
    {
        return $this->getNombre();
    }
    public function __construct()
    {
        $this->planes = new ArrayCollection();
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

    public function getProveedor(): ?Proveedor
    {
        return $this->proveedor;
    }

    public function setProveedor(?Proveedor $proveedor): self
    {
        $this->proveedor = $proveedor;

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
            $plane->setServicio($this);
        }

        return $this;
    }

    public function removePlane(Plan $plane): self
    {
        if ($this->planes->removeElement($plane)) {
            // set the owning side to null (unless already changed)
            if ($plane->getServicio() === $this) {
                $plane->setServicio(null);
            }
        }

        return $this;
    }
}
