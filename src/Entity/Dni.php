<?php

namespace App\Entity;

use App\Repository\DniRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DniRepository::class)
 * @UniqueEntity(fields={"numero"}, message="Ya existe un registro con este DNI")
 */
class Dni
{
    const RUC ='04';
    const CEDULA ='05';
    const PASAPORTE ='06';
    const CONSUMIDOR ='07';
    const IDENTIFIACION_EXTRANJERA ='08';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fecha_exp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto_frontal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto_posterior;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $tipo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFechaExp(): ?\DateTimeInterface
    {
        return $this->fecha_exp;
    }

    public function setFechaExp(?\DateTimeInterface $fecha_exp): self
    {
        $this->fecha_exp = $fecha_exp;

        return $this;
    }

    public function getFotoFrontal(): ?string
    {
        return $this->foto_frontal;
    }

    public function setFotoFrontal(?string $foto_frontal): self
    {
        $this->foto_frontal = $foto_frontal;

        return $this;
    }

    public function getFotoPosterior(): ?string
    {
        return $this->foto_posterior;
    }

    public function setFotoPosterior(?string $foto_posterior): self
    {
        $this->foto_posterior = $foto_posterior;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
