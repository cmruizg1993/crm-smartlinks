<?php

namespace App\Entity;

use App\Repository\MensajeWtpOutRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajeWtpOutRepository::class)
 */
class MensajeWtpOut
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ContactWtp::class, inversedBy="mensajes_in")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity=Mensaje::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mensaje;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContact(): ?ContactWtp
    {
        return $this->contact;
    }

    public function setContact(?ContactWtp $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getMensaje(): ?Mensaje
    {
        return $this->mensaje;
    }

    public function setMensaje(?Mensaje $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }
}
