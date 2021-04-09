<?php

namespace App\Entity;

use App\Repository\CargoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CargoRepository::class)
 */
class Cargo
{
    public const VENDEDOR = 'VN';
    public const OPERADOR = 'OP';
    public const INSTALADOR = 'IT';
    public const VENDEDORINSTALADOR = 'VI';
    public const CONTADOR = 'CN';


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
     * @ORM\Column(type="string", length=2, unique=true)
     */
    private $codigo;

    /**
     * @ORM\OneToMany(targetEntity=Colaborador::class, mappedBy="cargo")
     */
    private $colaboradores;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNombre();
    }

    public function __construct()
    {
        $this->colaboradores = new ArrayCollection();
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
            $colaboradore->setCargo($this);
        }

        return $this;
    }

    public function removeColaboradore(Colaborador $colaboradore): self
    {
        if ($this->colaboradores->removeElement($colaboradore)) {
            // set the owning side to null (unless already changed)
            if ($colaboradore->getCargo() === $this) {
                $colaboradore->setCargo(null);
            }
        }

        return $this;
    }
}
