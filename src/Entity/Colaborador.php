<?php

namespace App\Entity;

use App\Repository\ColaboradorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColaboradorRepository::class)
 */
class Colaborador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombres;


    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class, inversedBy="colaboradores")
     */
    private $cargo;

    /**
     * @ORM\ManyToMany(targetEntity=Proveedor::class, inversedBy="colaboradores")
     */
    private $proveedores;

    /**
     * @ORM\ManyToOne(targetEntity=Parroquia::class, inversedBy="colaboradores")
     */
    private $parroquia;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, mappedBy="colaborador", cascade={"persist", "remove"})
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity=Contrato::class, mappedBy="instalador")
     */
    private $contratos;

    /**
     * @ORM\OneToMany(targetEntity=Orden::class, mappedBy="tecnico", cascade={"persist", "remove"})
     */
    private $ordenes;

    /**
     * @ORM\OneToMany(targetEntity=SAN::class, mappedBy="vendedor",cascade={"persist", "remove"})
     */
    private $sans;

    /**
     * @ORM\Column(type="string", length=18, unique=true)
     */
    private $cedula;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoIP;



    public function __toString(): string
    {
        return $this->getUsuario()->getEmail() . ' ' . $this->nombres;
    }
    public function __construct()
    {
        $this->proveedores = new ArrayCollection();
        $this->contratos = new ArrayCollection();
        $this->ordenes = new ArrayCollection();
        $this->sans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * @return Collection|Proveedor[]
     */
    public function getProveedores(): Collection
    {
        return $this->proveedores;
    }

    public function addProveedore(Proveedor $proveedore): self
    {
        if (!$this->proveedores->contains($proveedore)) {
            $this->proveedores[] = $proveedore;
        }

        return $this;
    }

    public function removeProveedore(Proveedor $proveedore): self
    {
        $this->proveedores->removeElement($proveedore);

        return $this;
    }

    public function getParroquia(): ?Parroquia
    {
        return $this->parroquia;
    }

    public function setParroquia(?Parroquia $parroquia): self
    {
        $this->parroquia = $parroquia;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        // unset the owning side of the relation if necessary
        if ($usuario === null && $this->usuario !== null) {
            $this->usuario->setColaborador(null);
        }

        // set the owning side of the relation if necessary
        if ($usuario !== null && $usuario->getColaborador() !== $this) {
            $usuario->setColaborador($this);
        }

        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return Collection|Contrato[]
     */
    public function getContratos(): Collection
    {
        return $this->contratos;
    }

    public function addContrato(Contrato $contrato): self
    {
        if (!$this->contratos->contains($contrato)) {
            $this->contratos[] = $contrato;
            $contrato->setInstalador($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): self
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getInstalador() === $this) {
                $contrato->setInstalador(null);
            }
        }

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
            $ordene->setTecnico($this);
        }

        return $this;
    }

    public function removeOrdene(Orden $ordene): self
    {
        if ($this->ordenes->removeElement($ordene)) {
            // set the owning side to null (unless already changed)
            if ($ordene->getTecnico() === $this) {
                $ordene->setTecnico(null);
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
            $san->setVendedor($this);
        }

        return $this;
    }

    public function removeSan(SAN $san): self
    {
        if ($this->sans->removeElement($san)) {
            // set the owning side to null (unless already changed)
            if ($san->getVendedor() === $this) {
                $san->setVendedor(null);
            }
        }

        return $this;
    }

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(string $cedula): self
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getCodigoIP(): ?int
    {
        return $this->codigoIP;
    }

    public function setCodigoIP(?int $codigoIP): self
    {
        $this->codigoIP = $codigoIP;

        return $this;
    }

}
