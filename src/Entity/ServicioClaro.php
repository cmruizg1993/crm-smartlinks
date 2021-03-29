<?php

namespace App\Entity;

use App\Repository\ServicioClaroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServicioClaroRepository::class)
 */
class ServicioClaro
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
     * @ORM\OneToMany(targetEntity=PlanesClaro::class, mappedBy="servicio")
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
            $plane->setServicio($this);
        }

        return $this;
    }

    public function removePlane(PlanesClaro $plane): self
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
