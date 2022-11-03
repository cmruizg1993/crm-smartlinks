<?php

namespace App\Entity;

use App\Repository\FacturaDetalleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaDetalleRepository::class)
 */
class FacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $descuento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $descuento): self
    {
        $this->descuento = $descuento;

        return $this;
    }
}
