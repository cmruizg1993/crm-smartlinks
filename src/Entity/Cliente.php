<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClienteRepository::class)
 */
class Cliente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombres;


    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $estado;


    /**
     * @ORM\OneToMany(targetEntity=Contrato::class, mappedBy="cliente")
     */
    private $contratos;

    /**
     * HACE REFERENCIA A LOS CONTRATOS CON CLARO
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="cliente")
     */
    private $solicitudes;

    /**
     * @ORM\OneToOne(targetEntity=Dni::class, cascade={"persist", "remove"})
     */
    private $dni;

    /**
     * @ORM\OneToMany(targetEntity=SAN::class, mappedBy="cliente",cascade={"persist", "remove"})
     */
    private $sans;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fingerprint;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $genero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nacionalidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residencia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="string", length=18, nullable=true)
     */
    private $telefonoFijo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $otro;
    public function __toString()
    {
        if($this->dni){
            return $this->nombres.' - '. $this->getDni()->getNumero();
        }
        return $this->nombres;
    }
    public function __construct()
    {
        $this->contratos = new ArrayCollection();
        $this->solicitudes = new ArrayCollection();
        $this->sans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * @return Collection|Contrato[]
     */
    public function getContratos(): Collection
    {
        return $this->contratos;
    }

    public function addContrato(Contrato $contrato): self
    {
        if (!$this->contratos->contains($contrato)) {
            $this->contratos[] = $contrato;
            $contrato->setCliente($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): self
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getCliente() === $this) {
                $contrato->setCliente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): self
    {
        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes[] = $solicitude;
            $solicitude->setCliente($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getCliente() === $this) {
                $solicitude->setCliente(null);
            }
        }

        return $this;
    }

    public function getDni(): ?Dni
    {
        return $this->dni;
    }

    public function setDni(?Dni $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * @return Collection|SAN[]
     */
    public function getSans(): Collection
    {
        return $this->sans;
    }

    public function addSan(SAN $san): self
    {
        if (!$this->sans->contains($san)) {
            $this->sans[] = $san;
            $san->setCliente($this);
        }

        return $this;
    }

    public function removeSan(SAN $san): self
    {
        if ($this->sans->removeElement($san)) {
            // set the owning side to null (unless already changed)
            if ($san->getCliente() === $this) {
                $san->setCliente(null);
            }
        }

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getFingerprint(): ?string
    {
        return $this->fingerprint;
    }

    public function setFingerprint(?string $fingerprint): self
    {
        $this->fingerprint = $fingerprint;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(?string $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(?string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getResidencia(): ?string
    {
        return $this->residencia;
    }

    public function setResidencia(?string $residencia): self
    {
        $this->residencia = $residencia;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getEstadoCivil(): ?string
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(?string $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getTelefonoFijo(): ?string
    {
        return $this->telefonoFijo;
    }

    public function setTelefonoFijo(?string $telefonoFijo): self
    {
        $this->telefonoFijo = $telefonoFijo;

        return $this;
    }

    public function getOtro(): ?string
    {
        return $this->otro;
    }

    public function setOtro(?string $otro): self
    {
        $this->otro = $otro;

        return $this;
    }
}
