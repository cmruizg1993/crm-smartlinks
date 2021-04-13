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
     * @ORM\Column(type="string", length=18, nullable=true)
     */
    private $cedula;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoIP;


    /**
     * @ORM\OneToMany(targetEntity=Cliente::class, mappedBy="vendedor")
     */
    private $clientes;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="vendedor")
     */
    private $solicitudes;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $ruc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $razon;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $factura;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $iva;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $retFuente;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $retIva;


    public function __toString()
    {
        return $this->nombres;
    }

    public function __construct()
    {
        $this->proveedores = new ArrayCollection();
        $this->contratos = new ArrayCollection();
        $this->ordenes = new ArrayCollection();
        $this->sans = new ArrayCollection();
        $this->ventasHughes = new ArrayCollection();
        $this->clientes = new ArrayCollection();
        $this->solicitudes = new ArrayCollection();
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

    /**
     * @return Collection|Cliente[]
     */
    public function getClientes(): Collection
    {
        return $this->clientes;
    }

    public function addCliente(Cliente $cliente): self
    {
        if (!$this->clientes->contains($cliente)) {
            $this->clientes[] = $cliente;
            $cliente->setVendedor($this);
        }

        return $this;
    }

    public function removeCliente(Cliente $cliente): self
    {
        if ($this->clientes->removeElement($cliente)) {
            // set the owning side to null (unless already changed)
            if ($cliente->getVendedor() === $this) {
                $cliente->setVendedor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): self
    {
        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes[] = $solicitude;
            $solicitude->setVendedor($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getVendedor() === $this) {
                $solicitude->setVendedor(null);
            }
        }

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

    public function getRazon(): ?string
    {
        return $this->razon;
    }

    public function setRazon(?string $razon): self
    {
        $this->razon = $razon;

        return $this;
    }

    public function getFactura(): ?bool
    {
        return $this->factura;
    }

    public function setFactura(?bool $factura): self
    {
        $this->factura = $factura;

        return $this;
    }

    public function getIva(): ?int
    {
        return $this->iva;
    }

    public function setIva(?int $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getRetFuente(): ?float
    {
        return $this->retFuente;
    }

    public function setRetFuente(?float $retFuente): self
    {
        $this->retFuente = $retFuente;

        return $this;
    }

    public function getRetIva(): ?float
    {
        return $this->retIva;
    }

    public function setRetIva(?float $retIva): self
    {
        $this->retIva = $retIva;

        return $this;
    }

}
