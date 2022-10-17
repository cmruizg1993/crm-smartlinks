<?php

namespace App\Entity;

use App\Repository\OpcionCatalogoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpcionCatalogoRepository::class)
 */
class OpcionCatalogo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Catalogo::class, inversedBy="opciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $texto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatalogo(): ?Catalogo
    {
        return $this->catalogo;
    }

    public function setCatalogo(?Catalogo $catalogo): self
    {
        $this->catalogo = $catalogo;

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

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }
}
