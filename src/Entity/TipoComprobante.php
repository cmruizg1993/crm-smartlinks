<?php

namespace App\Entity;

use App\Repository\TipoComprobanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoComprobanteRepository::class)
 */
class TipoComprobante
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $codigo;


    public function __construct()
    {
        $this->secuencials = new ArrayCollection();
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNombre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
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

    /**
     * @return Collection<int, Factura>
     */
    public function getFacturas(): Collection
    {
        return $this->facturas;
    }

    public function addFactura(Factura $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas[] = $factura;
            $factura->setComprobante($this);
        }

        return $this;
    }

    public function removeFactura(Factura $factura): self
    {
        if ($this->facturas->removeElement($factura)) {
            // set the owning side to null (unless already changed)
            if ($factura->getComprobante() === $this) {
                $factura->setComprobante(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Secuencial>
     */
    public function getSecuencials(): Collection
    {
        return $this->secuencials;
    }

    public function addSecuencial(Secuencial $secuencial): self
    {
        if (!$this->secuencials->contains($secuencial)) {
            $this->secuencials[] = $secuencial;
            $secuencial->setPuntoEmision($this);
        }

        return $this;
    }

    public function removeSecuencial(Secuencial $secuencial): self
    {
        if ($this->secuencials->removeElement($secuencial)) {
            // set the owning side to null (unless already changed)
            if ($secuencial->getPuntoEmision() === $this) {
                $secuencial->setPuntoEmision(null);
            }
        }

        return $this;
    }
}
