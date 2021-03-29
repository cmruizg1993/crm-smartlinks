<?php

namespace App\Entity;

use App\Repository\ParroquiaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParroquiaRepository::class)
 */
class Parroquia
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
     * @ORM\ManyToOne(targetEntity=Canton::class, inversedBy="parroquias", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $canton;

    /**
     * @ORM\OneToMany(targetEntity=Colaborador::class, mappedBy="parroquia")
     */
    private $colaboradores;

    /**
     * @ORM\OneToMany(targetEntity=SAN::class, mappedBy="parroquia")
     */
    private $sans;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        //return $this->getCanton()->getProvincia()->getNombre().
        //    ', '.$this->getCanton()->getNombre().', '.$this->getNombre();
        return $this->getNombre();
    }

    public function __construct()
    {
        $this->colaboradores = new ArrayCollection();
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

    public function getCanton(): ?Canton
    {
        return $this->canton;
    }

    public function setCanton(?Canton $canton): self
    {
        $this->canton = $canton;

        return $this;
    }

    /**
     * @return Collection|Colaborador[]
     */
    public function getColaboradores(): Collection
    {
        return $this->colaboradores;
    }

    public function addColaboradore(Colaborador $colaboradore): self
    {
        if (!$this->colaboradores->contains($colaboradore)) {
            $this->colaboradores[] = $colaboradore;
            $colaboradore->setParroquia($this);
        }

        return $this;
    }

    public function removeColaboradore(Colaborador $colaboradore): self
    {
        if ($this->colaboradores->removeElement($colaboradore)) {
            // set the owning side to null (unless already changed)
            if ($colaboradore->getParroquia() === $this) {
                $colaboradore->setParroquia(null);
            }
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
            $san->setParroquia($this);
        }

        return $this;
    }

    public function removeSan(SAN $san): self
    {
        if ($this->sans->removeElement($san)) {
            // set the owning side to null (unless already changed)
            if ($san->getParroquia() === $this) {
                $san->setParroquia(null);
            }
        }

        return $this;
    }
}
