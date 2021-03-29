<?php

namespace App\Entity;

use App\Repository\ProvinciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProvinciaRepository::class)
 */
class Provincia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Canton::class, mappedBy="provincia",cascade={"persist"})
     */
    private $cantones;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNombre();
    }

    public function __construct()
    {
        $this->cantones = new ArrayCollection();
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
     * @return Collection|Canton[]
     */
    public function getCantones(): Collection
    {
        return $this->cantones;
    }

    public function addCantone(Canton $cantone): self
    {
        if (!$this->cantones->contains($cantone)) {
            $this->cantones[] = $cantone;
            $cantone->setProvincia($this);
        }

        return $this;
    }

    public function removeCantone(Canton $cantone): self
    {
        if ($this->cantones->removeElement($cantone)) {
            // set the owning side to null (unless already changed)
            if ($cantone->getProvincia() === $this) {
                $cantone->setProvincia(null);
            }
        }

        return $this;
    }
}
