<?php

namespace App\Entity;

use App\Repository\SeriadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeriadoRepository::class)
 */
class Seriado
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
    private $serie;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class, inversedBy="seriados")
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipo;

    /**
     * @ORM\ManyToMany(targetEntity=Orden::class, mappedBy="equipos")
     */
    private $ordenes;


    public function __construct()
    {
        $this->ordenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function setSerie(string $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): self
    {
        $this->equipo = $equipo;

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
            $ordene->addEquipo($this);
        }

        return $this;
    }

    public function removeOrdene(Orden $ordene): self
    {
        if ($this->ordenes->removeElement($ordene)) {
            $ordene->removeEquipo($this);
        }

        return $this;
    }

}
