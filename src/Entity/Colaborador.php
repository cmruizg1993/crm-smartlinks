<?php

namespace App\Entity;

use App\Repository\ColaboradorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=ColaboradorRepository::class)
 */
class Colaborador
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
     * @ORM\ManyToOne(targetEntity=Cargo::class, inversedBy="colaboradores")
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity=Parroquia::class, inversedBy="colaboradores")
     */
    private $parroquia;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity=Orden::class, mappedBy="tecnico", cascade={"persist"})
     */
    private $ordenes;

    /**
     * @ORM\OneToMany(targetEntity=Contrato::class, mappedBy="vendedor",cascade={"persist"})
     */
    private $Contratos;

    /**
     * @ORM\Column(type="string", length=18, nullable=true)
     */
    private $cedula;


    /**
     * @ORM\OneToMany(targetEntity=Cliente::class, mappedBy="vendedor")
     */
    private $clientes;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="vendedor")
     */
    private $solicitudes;

    /**
     * @ORM\ManyToOne(targetEntity=PuntoEmision::class)
     */
    private $puntoEmision;


    public function __toString()
    {
        return $this->nombres;
    }

    public function __construct()
    {
        $this->contratos = new ArrayCollection();
        $this->ordenes = new ArrayCollection();
        $this->Contratos = new ArrayCollection();
        $this->ventasHughes = new ArrayCollection();
        $this->clientes = new ArrayCollection();
        $this->solicitudes = new ArrayCollection();
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

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        // unset the owning side of the relation if necessary
        if ($usuario === null && $this->usuario !== null) {
            $this->usuario->setColaborador(null);
        }

        // set the owning side of the relation if necessary
        if ($usuario !== null && $usuario->getColaborador() !== $this) {
            $usuario->setColaborador($this);
        }

        $this->usuario = $usuario;

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
            $contrato->setInstalador($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): self
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getInstalador() === $this) {
                $contrato->setInstalador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Orden[]
     */
    public function getOrdenes(): Collection
    {
        return $this->ordenes;
    }

    public function addOrdene(Orden $ordene): self
    {
        if (!$this->ordenes->contains($ordene)) {
            $this->ordenes[] = $ordene;
            $ordene->setTecnico($this);
        }

        return $this;
    }

    public function removeOrdene(Orden $ordene): self
    {
        if ($this->ordenes->removeElement($ordene)) {
            // set the owning side to null (unless already changed)
            if ($ordene->getTecnico() === $this) {
                $ordene->setTecnico(null);
            }
        }

        return $this;
    }

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(string $cedula): self
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getCodigoIP(): ?int
    {
        return $this->codigoIP;
    }

    public function setCodigoIP(?int $codigoIP): self
    {
        $this->codigoIP = $codigoIP;

        return $this;
    }

    /**
     * @return Collection|Cliente[]
     */
    public function getClientes(): Collection
    {
        return $this->clientes;
    }

    public function addCliente(Cliente $cliente): self
    {
        if (!$this->clientes->contains($cliente)) {
            $this->clientes[] = $cliente;
            $cliente->setVendedor($this);
        }

        return $this;
    }

    public function removeCliente(Cliente $cliente): self
    {
        if ($this->clientes->removeElement($cliente)) {
            // set the owning side to null (unless already changed)
            if ($cliente->getVendedor() === $this) {
                $cliente->setVendedor(null);
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
            $solicitude->setVendedor($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getVendedor() === $this) {
                $solicitude->setVendedor(null);
            }
        }

        return $this;
    }

    public function getRuc(): ?string
    {
        return $this->ruc;
    }

    public function setRuc(?string $ruc): self
    {
        $this->ruc = $ruc;

        return $this;
    }

    public function getRazon(): ?string
    {
        return $this->razon;
    }

    public function setRazon(?string $razon): self
    {
        $this->razon = $razon;

        return $this;
    }

    public function getFactura(): ?bool
    {
        return $this->factura;
    }

    public function setFactura(?bool $factura): self
    {
        $this->factura = $factura;

        return $this;
    }

    public function getIva(): ?int
    {
        return $this->iva;
    }

    public function setIva(?int $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getRetFuente(): ?float
    {
        return $this->retFuente;
    }

    public function setRetFuente(?float $retFuente): self
    {
        $this->retFuente = $retFuente;

        return $this;
    }

    public function getRetIva(): ?float
    {
        return $this->retIva;
    }

    public function setRetIva(?float $retIva): self
    {
        $this->retIva = $retIva;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload){
        $cedula = $this->cedula;
        $isvalid = $this->validarCedula($cedula);
        if(!$isvalid){
            $context->buildViolation('CI is not valid!')
                ->atPath('cedula')
                ->addViolation();
        }
        $direccion = $this->direccion;
        if(!trim($direccion)){
            $context->buildViolation('Please, enter your Address')
                ->atPath('direccion')
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

    public function getPuntoEmision(): ?PuntoEmision
    {
        return $this->puntoEmision;
    }

    public function setPuntoEmision(?PuntoEmision $puntoEmision): self
    {
        $this->puntoEmision = $puntoEmision;

        return $this;
    }
}
