<?php

namespace App\Entity;

use App\Repository\TempRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TempRepository::class)
 */
class Temp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $mensajes = [];

    /**
     * @ORM\OneToOne(targetEntity=ContactWtp::class, inversedBy="temp", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensajes(): ?array
    {
        return $this->mensajes;
    }

    public function setMensajes(?array $mensajes): self
    {
        $this->mensajes = $mensajes;

        return $this;
    }

    public function getContact(): ?ContactWtp
    {
        return $this->contact;
    }

    public function setContact(ContactWtp $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
