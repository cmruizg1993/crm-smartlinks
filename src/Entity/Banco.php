<?php

namespace App\Entity;

use App\Repository\BancoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BancoRepository::class)
 */
class Banco
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=CuentaBancaria::class, mappedBy="banco")
     */
    private $cuentas;

    public function __construct()
    {
        $this->cuentas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|CuentaBancaria[]
     */
    public function getCuentas(): Collection
    {
        return $this->cuentas;
    }

    public function addCuenta(CuentaBancaria $cuenta): self
    {
        if (!$this->cuentas->contains($cuenta)) {
            $this->cuentas[] = $cuenta;
            $cuenta->setBanco($this);
        }

        return $this;
    }

    public function removeCuenta(CuentaBancaria $cuenta): self
    {
        if ($this->cuentas->removeElement($cuenta)) {
            // set the owning side to null (unless already changed)
            if ($cuenta->getBanco() === $this) {
                $cuenta->setBanco(null);
            }
        }

        return $this;
    }
}
