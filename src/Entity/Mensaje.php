<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajeRepository::class)
 */
class Mensaje
{
    const ERROR =0;
    const INPUT  =1;
    const SELECT =2;
    const OUTPUT =3;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $texto;

    /**
     * @ORM\ManyToOne(targetEntity=Mensaje::class, inversedBy="hijos")
     */
    private $padre;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="padre")
     */
    private $hijos;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipo;

    /**
     * @ORM\OneToOne(targetEntity=Mensaje::class, cascade={"persist"})
     */
    private $siguiente;

    public function __construct()
    {
        $this->hijos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getPadre(): ?self
    {
        return $this->padre;
    }

    public function setPadre(?self $padre): self
    {
        $this->padre = $padre;

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
            $hijo->setPadre($this);
        }

        return $this;
    }

    public function removeHijo(self $hijo): self
    {
        if ($this->hijos->removeElement($hijo)) {
            // set the owning side to null (unless already changed)
            if ($hijo->getPadre() === $this) {
                $hijo->setPadre(null);
            }
        }

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getSiguiente(): ?self
    {
        return $this->siguiente;
    }

    public function setSiguiente(?self $siguiente): self
    {
        $this->siguiente = $siguiente;

        return $this;
    }
}
