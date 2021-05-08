<?php

namespace App\Entity;

use App\Repository\ContactWtpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactWtpRepository::class)
 */
class ContactWtp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=18)
     */
    private $uid;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pic;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=MensajeWtp::class, mappedBy="contact", cascade={"persist"})
     */
    private $mensajes;

    /**
     * @ORM\OneToMany(targetEntity=MensajeWtpOut::class, mappedBy="contact")
     */
    private $mensajes_in;

    /**
     * @ORM\OneToOne(targetEntity=Temp::class, mappedBy="contact", cascade={"persist", "remove"})
     */
    private $temp;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getUid().'-'.$this->getName();
    }

    public function __construct()
    {
        $this->mensajes = new ArrayCollection();
        $this->mensajes_in = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getPic(): ?string
    {
        return $this->pic;
    }

    public function setPic(?string $pic): self
    {
        $this->pic = $pic;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|MensajeWtp[]
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(MensajeWtp $mensaje): self
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes[] = $mensaje;
            $mensaje->setContact($this);
        }

        return $this;
    }

    public function removeMensaje(MensajeWtp $mensaje): self
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getContact() === $this) {
                $mensaje->setContact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MensajeWtpOut[]
     */
    public function getMensajesIn(): Collection
    {
        return $this->mensajes_in;
    }

    public function addMensajesIn(MensajeWtpOut $mensajesIn): self
    {
        if (!$this->mensajes_in->contains($mensajesIn)) {
            $this->mensajes_in[] = $mensajesIn;
            $mensajesIn->setContact($this);
        }

        return $this;
    }

    public function removeMensajesIn(MensajeWtpOut $mensajesIn): self
    {
        if ($this->mensajes_in->removeElement($mensajesIn)) {
            // set the owning side to null (unless already changed)
            if ($mensajesIn->getContact() === $this) {
                $mensajesIn->setContact(null);
            }
        }

        return $this;
    }

    public function getTemp(): ?Temp
    {
        return $this->temp;
    }

    public function setTemp(Temp $temp): self
    {
        // set the owning side of the relation if necessary
        if ($temp->getContact() !== $this) {
            $temp->setContact($this);
        }

        $this->temp = $temp;

        return $this;
    }
}
