<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=ClienteRepository::class)
 * @UniqueEntity(fields={"dni"}, message="Ya existe un registro con este DNI")
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
     * HACE REFERENCIA A LOS CONTRATOS CON CLARO
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="cliente", cascade={"persist"})
     */
    private $solicitudes;

    /**
     * @ORM\OneToOne(targetEntity=Dni::class, cascade={"persist"})
     */
    private $dni;

    /**
     * @ORM\OneToMany(targetEntity=Contrato::class, mappedBy="cliente",cascade={"persist"})
     */
    private $Contratos;

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

    /**
     * @ORM\ManyToOne(targetEntity=Colaborador::class, inversedBy="clientes")
     */
    private $vendedor;

    /**
     * @ORM\OneToMany(targetEntity=Factura::class, mappedBy="cliente")
     */
    private $facturas;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $esTerceraEdad;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $esDiscapacitado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreComercial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenciaDireccion;

    /**
     * @ORM\ManyToOne(targetEntity=Parroquia::class)
     */
    private $parroquia;
    public function __toString()
    {
        return $this->nombres ? $this->nombres : ''. $this->getId();
    }
    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->Contratos = new ArrayCollection();
        $this->facturas = new ArrayCollection();
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

    public function setEmail(?string $email): self
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
     * @return Collection|Contrato[]
     */
    public function getContratos(): Collection
    {
        return $this->Contratos;
    }

    public function addContrato(Contrato $Contrato): self
    {
        if (!$this->Contratos->contains($Contrato)) {
            $this->Contratos[] = $Contrato;
            $Contrato->setCliente($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $Contrato): self
    {
        if ($this->Contratos->removeElement($Contrato)) {
            // set the owning side to null (unless already changed)
            if ($Contrato->getCliente() === $this) {
                $Contrato->setCliente(null);
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

    public function getVendedor(): ?Colaborador
    {
        return $this->vendedor;
    }

    public function setVendedor(?Colaborador $vendedor): self
    {
        $this->vendedor = $vendedor;

        return $this;
    }
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload){
        $cedula = $this->getDni()->getNumero();
        $isvalid = $this->validarCedula($cedula);
        if(!$isvalid && $this->getDni()->getTipo() == Dni::CEDULA){
            $context->buildViolation('CI is not valid!')
                ->atPath('cedula')
                ->addViolation();
        }
        $nombres = $this->nombres;
        if(!trim($nombres)){
            $context->buildViolation('Please, enter your names')
                ->atPath('nombres')
                ->addViolation();
        }
    }
    private function validarCedula($cedula){
        $tam = strlen($cedula);
        if($tam!=10){
            return false;
        }
        if(is_integer($cedula)){
            return false;
        }

        $codigoProvincia = substr($cedula,0,2);
        $digitoMenorA6 = substr($cedula,2,1);
        $secuencia = substr($cedula,3,1);
        $digitoVerificador = substr($cedula,9,1);
        if($codigoProvincia<0 || $codigoProvincia > 24){
            return false;
        }
        if($digitoMenorA6>=6){
            return false;
        }
        $arrayCoeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $arrayDeDigitos = str_split($cedula,1);
        $suma = 0;
        foreach($arrayDeDigitos as $index=>$value){
            if($index < 9){
                $digito = ($value);
                $producto = $digito*$arrayCoeficientes[$index] < 10 ? $digito*$arrayCoeficientes[$index]: $digito*$arrayCoeficientes[$index]-9;
                $suma += $producto;
            }
        }


        $decenaSuperior=null;
        if($suma%10 > 0){
            $decenaSuperior = (intval(($suma/10)) + 1) * 10;
        }else{
            $decenaSuperior = $suma;
        }

        $resultado = $decenaSuperior - $suma ;

        if($resultado == $digitoVerificador){
            return true ;
        }
        return false;
    }

    /**
     * @return Collection|Factura[]
     */
    public function getFacturas(): Collection
    {
        return $this->facturas;
    }

    public function addFactura(Factura $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas[] = $factura;
            $factura->setCliente($this);
        }

        return $this;
    }

    public function removeFactura(Factura $factura): self
    {
        if ($this->facturas->removeElement($factura)) {
            // set the owning side to null (unless already changed)
            if ($factura->getCliente() === $this) {
                $factura->setCliente(null);
            }
        }

        return $this;
    }

    public function getEsTerceraEdad(): ?bool
    {
        return $this->esTerceraEdad;
    }

    public function setEsTerceraEdad(?bool $esTerceraEdad): self
    {
        $this->esTerceraEdad = $esTerceraEdad;

        return $this;
    }

    public function getEsDiscapacitado(): ?bool
    {
        return $this->esDiscapacitado;
    }

    public function setEsDiscapacitado(?bool $esDiscapacitado): self
    {
        $this->esDiscapacitado = $esDiscapacitado;

        return $this;
    }
    public function getData(){
        $data["name"] = $this->getNombres();
        $data["genre"] = $this->getGenero();
        $data["residence"] = $this->getResidencia();
        $data["nationality"] = $this->getNacionalidad();
        $data["streets"] = $this->getDireccion();
        $data["fingerprint"] = $this->getFingerprint();
        $data["civilstate"] = $this->getEstadoCivil();
        $data["dob"] = $this->getFechaNacimiento() ? $this->getFechaNacimiento()->format('d/m/Y'): null;
        $data["email"] = $this->getEmail();
        $data["dni_type"] = $this->getDni()->getTipo();
        $data["phone"] = $this->getTelefono();
        $data["fix_phone"] = $this->getTelefonoFijo();
        $data["exp_date"] = $this->getDni()->getFechaExp() ? $this->getDni()->getFechaExp()->format('d/m/Y'):null;
        $data["dni_type"] = $this->getDni()->getTipo();
        //dump($data);
        return $data;
    }

    public function getNombreComercial(): ?string
    {
        return $this->nombreComercial;
    }

    public function setNombreComercial(?string $nombreComercial): self
    {
        $this->nombreComercial = $nombreComercial;

        return $this;
    }

    public function getReferenciaDireccion(): ?string
    {
        return $this->referenciaDireccion;
    }

    public function setReferenciaDireccion(?string $referenciaDireccion): self
    {
        $this->referenciaDireccion = $referenciaDireccion;

        return $this;
    }

    public function getParroquia(): ?Parroquia
    {
        return $this->parroquia;
    }

    public function setParroquia(?Parroquia $parroquia): self
    {
        $this->parroquia = $parroquia;

        return $this;
    }
}
