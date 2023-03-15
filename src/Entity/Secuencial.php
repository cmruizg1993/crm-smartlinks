<?php

namespace App\Entity;

use App\Repository\SecuencialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecuencialRepository::class)
 */
class Secuencial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TipoComprobante::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoComprobante;

    /**
     * @ORM\Column(type="integer")
     */
    private $inicio = 1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $actual;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=PuntoEmision::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $puntoEmision;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInicio(): ?int
    {
        return $this->inicio;
    }

    public function setInicio(int $inicio): self
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getActual(): ?int
    {
        return $this->actual;
    }

    public function setActual(?int $actual): self
    {
        $this->actual = $actual;

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

    public function getPuntoEmision(): ?PuntoEmision
    {
        return $this->puntoEmision;
    }

    public function setPuntoEmision(?PuntoEmision $puntoEmision): self
    {
        $this->puntoEmision = $puntoEmision;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
}
