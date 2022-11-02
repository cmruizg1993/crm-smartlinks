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

    /**
     * @ORM\OneToMany(targetEntity=PuntoEmision::class, mappedBy="tipoComprobante")
     */
    private $puntosEmision;

    public function __construct()
    {
        $this->puntosEmision = new ArrayCollection();
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
     * @return Collection|PuntoEmision[]
     */
    public function getPuntosEmision(): Collection
    {
        return $this->puntosEmision;
    }

    public function addPuntosEmision(PuntoEmision $puntosEmision): self
    {
        if (!$this->puntosEmision->contains($puntosEmision)) {
            $this->puntosEmision[] = $puntosEmision;
            $puntosEmision->setTipoComprobante($this);
        }

        return $this;
    }

    public function removePuntosEmision(PuntoEmision $puntosEmision): self
    {
        if ($this->puntosEmision->removeElement($puntosEmision)) {
            // set the owning side to null (unless already changed)
            if ($puntosEmision->getTipoComprobante() === $this) {
                $puntosEmision->setTipoComprobante(null);
            }
        }

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
}
