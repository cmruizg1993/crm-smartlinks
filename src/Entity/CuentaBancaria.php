<?php

namespace App\Entity;

use App\Repository\CuentaBancariaRepository;
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
}
