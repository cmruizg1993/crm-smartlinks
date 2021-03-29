<?php

namespace App\Entity;

use App\Repository\CantonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CantonRepository::class)
 */
class Canton
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Parroquia::class, mappedBy="canton",cascade={"persist"})
     */
    private $parroquias;

    /**
     * @ORM\ManyToOne(targetEntity=Provincia::class, inversedBy="cantones",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $provincia;
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->nombre;
    }

    public function __construct()
    {
        $this->parroquias = new ArrayCollection();
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

    /**
     * @return Collection|Parroquia[]
     */
    public function getParroquias(): Collection
    {
        return $this->parroquias;
    }

    public function addParroquia(Parroquia $parroquia): self
    {
        if (!$this->parroquias->contains($parroquia)) {
            $this->parroquias[] = $parroquia;
            $parroquia->setCanton($this);
        }

        return $this;
    }

    public function removeParroquia(Parroquia $parroquia): self
    {
        if ($this->parroquias->removeElement($parroquia)) {
            // set the owning side to null (unless already changed)
            if ($parroquia->getCanton() === $this) {
                $parroquia->setCanton(null);
            }
        }

        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincia $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }
}
