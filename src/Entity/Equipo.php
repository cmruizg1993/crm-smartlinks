<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipoRepository::class)
 */
class Equipo
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
    private $sku;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Seriado::class, mappedBy="equipo")
     */
    private $seriados;

    /**
     * @ORM\OneToMany(targetEntity=NoSeriado::class, mappedBy="equipo")
     */
    private $noSeriados;

    /**
     * @ORM\ManyToOne(targetEntity=TipoEquipo::class, inversedBy="equipos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipo;
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->nombre;
    }

    public function __construct()
    {
        $this->seriados = new ArrayCollection();
        $this->noSeriados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

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

    /**
     * @return Collection|Seriado[]
     */
    public function getSeriados(): Collection
    {
        return $this->seriados;
    }

    public function addSeriado(Seriado $seriado): self
    {
        if (!$this->seriados->contains($seriado)) {
            $this->seriados[] = $seriado;
            $seriado->setEquipo($this);
        }

        return $this;
    }

    public function removeSeriado(Seriado $seriado): self
    {
        if ($this->seriados->removeElement($seriado)) {
            // set the owning side to null (unless already changed)
            if ($seriado->getEquipo() === $this) {
                $seriado->setEquipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NoSeriado[]
     */
    public function getNoSeriados(): Collection
    {
        return $this->noSeriados;
    }

    public function addNoSeriado(NoSeriado $noSeriado): self
    {
        if (!$this->noSeriados->contains($noSeriado)) {
            $this->noSeriados[] = $noSeriado;
            $noSeriado->setEquipo($this);
        }

        return $this;
    }

    public function removeNoSeriado(NoSeriado $noSeriado): self
    {
        if ($this->noSeriados->removeElement($noSeriado)) {
            // set the owning side to null (unless already changed)
            if ($noSeriado->getEquipo() === $this) {
                $noSeriado->setEquipo(null);
            }
        }

        return $this;
    }

    public function getTipo(): ?TipoEquipo
    {
        return $this->tipo;
    }

    public function setTipo(?TipoEquipo $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
