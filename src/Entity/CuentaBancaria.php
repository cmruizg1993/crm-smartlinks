<?php

namespace App\Entity;

use App\Repository\CuentaBancariaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CuentaBancariaRepository::class)
 */
class CuentaBancaria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Banco::class, inversedBy="cuentas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $tipoCuenta;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $cedula;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $beneficiario;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $esCuentaEmpresarial=false;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="cuentaBancaria")
     */
    private $solicitudes;

    /**
     * @ORM\OneToMany(targetEntity=Pago::class, mappedBy="ctaBancaria", orphanRemoval=true)
     */
    private $pagos;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->pagos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBanco(): ?Banco
    {
        return $this->banco;
    }

    public function setBanco(?Banco $banco): self
    {
        $this->banco = $banco;

        return $this;
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->numero.'-'.$this->getBanco()->getNombre();
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTipoCuenta(): ?string
    {
        return $this->tipoCuenta;
    }

    public function setTipoCuenta(string $tipoCuenta): self
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(?string $cedula): self
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getBeneficiario(): ?string
    {
        return $this->beneficiario;
    }

    public function setBeneficiario(string $beneficiario): self
    {
        $this->beneficiario = $beneficiario;

        return $this;
    }

    public function getEsCuentaEmpresarial(): ?bool
    {
        return $this->esCuentaEmpresarial;
    }

    public function setEsCuentaEmpresarial(?bool $esCuentaEmpresarial): self
    {
        $this->esCuentaEmpresarial = $esCuentaEmpresarial;

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
            $solicitude->setCuentaBancaria($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getCuentaBancaria() === $this) {
                $solicitude->setCuentaBancaria(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pago[]
     */
    public function getPagos(): Collection
    {
        return $this->pagos;
    }

    public function addPago(Pago $pago): self
    {
        if (!$this->pagos->contains($pago)) {
            $this->pagos[] = $pago;
            $pago->setCtaBancaria($this);
        }

        return $this;
    }

    public function removePago(Pago $pago): self
    {
        if ($this->pagos->removeElement($pago)) {
            // set the owning side to null (unless already changed)
            if ($pago->getCtaBancaria() === $this) {
                $pago->setCtaBancaria(null);
            }
        }

        return $this;
    }
}
