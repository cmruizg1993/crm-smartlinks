<?php

namespace App\Entity;

use App\Repository\MovimientoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovimientoRepository::class)
 */
class Movimiento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ubicacionInvas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sitioInvas;

    /**
     * @ORM\Column(type="integer")
     */
    private $idInstall;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sitioIdInstall;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getUbicacionInvas(): ?string
    {
        return $this->ubicacionInvas;
    }

    public function setUbicacionInvas(string $ubicacionInvas): self
    {
        $this->ubicacionInvas = $ubicacionInvas;

        return $this;
    }

    public function getSitioInvas(): ?string
    {
        return $this->sitioInvas;
    }

    public function setSitioInvas(string $sitioInvas): self
    {
        $this->sitioInvas = $sitioInvas;

        return $this;
    }

    public function getIdInstall(): ?int
    {
        return $this->idInstall;
    }

    public function setIdInstall(int $idInstall): self
    {
        $this->idInstall = $idInstall;

        return $this;
    }

    public function getSitioIdInstall(): ?string
    {
        return $this->sitioIdInstall;
    }

    public function setSitioIdInstall(string $sitioIdInstall): self
    {
        $this->sitioIdInstall = $sitioIdInstall;

        return $this;
    }
}
