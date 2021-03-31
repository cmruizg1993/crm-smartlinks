<?php

namespace App\Entity;

use App\Repository\ComisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComisionRepository::class)
 */
class Comision
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $porcentaje;

    /**
     * @ORM\Column(type="integer")
     */
    private $limiteInferior;

    /**
     * @ORM\Column(type="integer")
     */
    private $limiteSuperior;

    /**
     * @ORM\ManyToOne(targetEntity=TipoOrden::class, inversedBy="comisiones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orden;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPorcentaje(): ?float
    {
        return $this->porcentaje;
    }

    public function setPorcentaje(float $porcentaje): self
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    public function getLimiteInferior(): ?int
    {
        return $this->limiteInferior;
    }

    public function setLimiteInferior(int $limiteInferior): self
    {
        $this->limiteInferior = $limiteInferior;

        return $this;
    }

    public function getLimiteSuperior(): ?int
    {
        return $this->limiteSuperior;
    }

    public function setLimiteSuperior(int $limiteSuperior): self
    {
        $this->limiteSuperior = $limiteSuperior;

        return $this;
    }

    public function getOrden(): ?TipoOrden
    {
        return $this->orden;
    }

    public function setOrden(?TipoOrden $orden): self
    {
        $this->orden = $orden;

        return $this;
    }
}
