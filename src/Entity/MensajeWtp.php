<?php

namespace App\Entity;

use App\Repository\MensajeWtpRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajeWtpRepository::class)
 */
class MensajeWtp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dtm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cuid;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $dir;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mediakey;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mimetype;

    /**
     * @ORM\ManyToOne(targetEntity=ContactWtp::class, inversedBy="mensajes", cascade={"persist"})
     */
    private $contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDtm(): ?int
    {
        return $this->dtm;
    }

    public function setDtm(?int $dtm): self
    {
        $this->dtm = $dtm;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getCuid(): ?string
    {
        return $this->cuid;
    }

    public function setCuid(?string $cuid): self
    {
        $this->cuid = $cuid;

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

    public function getDir(): ?string
    {
        return $this->dir;
    }

    public function setDir(?string $dir): self
    {
        $this->dir = $dir;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMediakey(): ?string
    {
        return $this->mediakey;
    }

    public function setMediakey(?string $mediakey): self
    {
        $this->mediakey = $mediakey;

        return $this;
    }

    public function getMimetype(): ?string
    {
        return $this->mimetype;
    }

    public function setMimetype(?string $mimetype): self
    {
        $this->mimetype = $mimetype;

        return $this;
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
}
