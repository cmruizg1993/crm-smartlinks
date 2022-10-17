<?php

namespace App\Entity;

use App\Repository\CatalogoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatalogoRepository::class)
 */
class Catalogo
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
     * @ORM\Column(type="string", length=10)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToMany(targetEntity=Catalogo::class, inversedBy="hijos")
     */
    private $catalogoPadre;

    /**
     * @ORM\ManyToMany(targetEntity=Catalogo::class, mappedBy="catalogoPadre")
     */
    private $hijos;

    /**
     * @ORM\OneToMany(targetEntity=OpcionCatalogo::class, mappedBy="catalogo", orphanRemoval=true)
     */
    private $opciones;

    public function __construct()
    {
        $this->catalogoPadre = new ArrayCollection();
        $this->hijos = new ArrayCollection();
        $this->opciones = new ArrayCollection();
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNombre();
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCatalogoPadre(): Collection
    {
        return $this->catalogoPadre;
    }

    public function addCatalogoPadre(self $catalogoPadre): self
    {
        if (!$this->catalogoPadre->contains($catalogoPadre)) {
            $this->catalogoPadre[] = $catalogoPadre;
        }

        return $this;
    }

    public function removeCatalogoPadre(self $catalogoPadre): self
    {
        $this->catalogoPadre->removeElement($catalogoPadre);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getHijos(): Collection
    {
        return $this->hijos;
    }

    public function addHijo(self $hijo): self
    {
        if (!$this->hijos->contains($hijo)) {
            $this->hijos[] = $hijo;
            $hijo->addCatalogoPadre($this);
        }

        return $this;
    }

    public function removeHijo(self $hijo): self
    {
        if ($this->hijos->removeElement($hijo)) {
            $hijo->removeCatalogoPadre($this);
        }

        return $this;
    }

    /**
     * @return Collection|OpcionCatalogo[]
     */
    public function getOpciones(): Collection
    {
        return $this->opciones;
    }

    public function addOpcione(OpcionCatalogo $opcione): self
    {
        if (!$this->opciones->contains($opcione)) {
            $this->opciones[] = $opcione;
            $opcione->setCatalogo($this);
        }

        return $this;
    }

    public function removeOpcione(OpcionCatalogo $opcione): self
    {
        if ($this->opciones->removeElement($opcione)) {
            // set the owning side to null (unless already changed)
            if ($opcione->getCatalogo() === $this) {
                $opcione->setCatalogo(null);
            }
        }

        return $this;
    }
}
