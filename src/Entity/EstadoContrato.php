<?php

namespace App\Entity;

use App\Repository\EstadoContratoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstadoContratoRepository::class)
 */
class EstadoContrato
{
    const NOMBRE_CATALOGO = 'est-cont';
    const CORTADO = 'C';
    const ACTIVO = 'A';
    const SUSPENDIDO = 'S';
    const EJECUTADO = 'E';
    const CORTESIA = 'CO';
    const INPAGO = 'I';
    const PRECANCELADO = 'P';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity=OpcionCatalogo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $estado;


    private $contrato;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getEstado(): ?OpcionCatalogo
    {
        return $this->estado;
    }

    public function setEstado(?OpcionCatalogo $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getContrato(): ?Contrato
    {
        return $this->contrato;
    }

    public function setContrato(?Contrato $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }
}
