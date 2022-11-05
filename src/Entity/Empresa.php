<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpresaRepository::class)
 */
class Empresa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $razonSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreComercial;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $ruc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $obligadoContabilidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $p12Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $p12Password;

    /**
     * @ORM\OneToMany(targetEntity=Usuario::class, mappedBy="empresa")
     */
    private $usuarios;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreConfiguracion(): ?string
    {
        return $this->nombreConfiguracion;
    }

    public function setNombreConfiguracion(string $nombreConfiguracion): self
    {
        $this->nombreConfiguracion = $nombreConfiguracion;

        return $this;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function setRazonSocial(?string $razonSocial): self
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    public function getNombreComercial(): ?string
    {
        return $this->nombreComercial;
    }

    public function setNombreComercial(?string $nombreComercial): self
    {
        $this->nombreComercial = $nombreComercial;

        return $this;
    }

    public function getRuc(): ?string
    {
        return $this->ruc;
    }

    public function setRuc(?string $ruc): self
    {
        $this->ruc = $ruc;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

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

    public function getObligadoContabilidad(): ?string
    {
        return $this->obligadoContabilidad;
    }

    public function setObligadoContabilidad(string $obligadoContabilidad): self
    {
        $this->obligadoContabilidad = $obligadoContabilidad;

        return $this;
    }

    public function getP12Name(): ?string
    {
        return $this->p12Name;
    }

    public function setP12Name(?string $p12Name): self
    {
        $this->p12Name = $p12Name;

        return $this;
    }

    public function getP12Password(): ?string
    {
        return $this->p12Password;
    }

    public function setP12Password(?string $p12Password): self
    {
        $this->p12Password = $p12Password;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
            $usuario->setEmpresa($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuarios->removeElement($usuario)) {
            // set the owning side to null (unless already changed)
            if ($usuario->getEmpresa() === $this) {
                $usuario->setEmpresa(null);
            }
        }

        return $this;
    }
}
