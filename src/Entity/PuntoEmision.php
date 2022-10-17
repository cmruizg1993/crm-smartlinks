<?php

namespace App\Entity;

use App\Repository\PuntoEmisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PuntoEmisionRepository::class)
 */
class PuntoEmision
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Establecimiento::class, inversedBy="puntosEmision")
     * @ORM\JoinColumn(nullable=false)
     */
    private $establecimiento;

    /**
     * @ORM\ManyToOne(targetEntity=TipoComprobante::class, inversedBy="puntosEmision")
     */
    private $tipoComprobante;
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getEstablecimiento()->getCodigo().' - '.$this->getCodigo();
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getEstablecimiento(): ?Establecimiento
    {
        return $this->establecimiento;
    }

    public function setEstablecimiento(?Establecimiento $establecimiento): self
    {
        $this->establecimiento = $establecimiento;

        return $this;
    }

    public function getTipoComprobante(): ?TipoComprobante
    {
        return $this->tipoComprobante;
    }

    public function setTipoComprobante(?TipoComprobante $tipoComprobante): self
    {
        $this->tipoComprobante = $tipoComprobante;

        return $this;
    }
}
