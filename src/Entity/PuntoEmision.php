<?php

namespace App\Entity;

use App\Repository\PuntoEmisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity=Factura::class, mappedBy="puntoEmision")
     */
    private $facturas;

    public function __construct()
    {
        $this->facturas = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        if(!$this->getCodigoEstablecimiento()) return ''. $this->getId();
        return $this->getEstablecimiento()->getCodigo().' - '.$this->getCodigo();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId($id): self
    {
        $this->id = $id;
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
    
    public function getSerie(){
        return $this->__toString();
    }
    public function getCodigoEstablecimiento(){
        if(!$this->getEstablecimiento()) return null;
        return $this->getEstablecimiento()->getCodigo();
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
            $factura->setPuntoEmision($this);
        }

        return $this;
    }

    public function removeFactura(Factura $factura): self
    {
        if ($this->facturas->removeElement($factura)) {
            // set the owning side to null (unless already changed)
            if ($factura->getPuntoEmision() === $this) {
                $factura->setPuntoEmision(null);
            }
        }

        return $this;
    }
}
