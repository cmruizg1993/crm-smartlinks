<?php

namespace App\Form;

use App\Entity\DetalleFactura;
use App\Entity\Factura;
use App\Entity\OpcionCatalogo;
use App\Entity\PuntoEmision;
use App\Entity\TipoComprobante;
use App\Repository\OpcionCatalogoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
    private $repositorioOpciones;
    public function __construct(OpcionCatalogoRepository $repositorioOpciones)
    {
        $this->repositorioOpciones = $repositorioOpciones;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('secuencial')
            ->add('formaPago')
            ->add('contrato')
            ->add('cliente')
            ->add('puntoEmision')
            ->add('tipoComprobante')
            ->add('fecha', DateType::class,['input'=>'string', 'widget'=>'single_text'])
            ->add('tipoAmbiente')
            ->add('serial')
            ->add('anioPago')
            ->add('mesPago')
            ->add('comprobantePago')
            ->add('propina')
            ->add('observaciones')
            ->add('detalles', CollectionType::class, ['entry_type'=>DetalleFacturaType::class, 'allow_add'=>true, 'allow_delete'=>true]);

        $builder->get('fecha')->addModelTransformer(new CallbackTransformer(
            function (? \DateTime $date = null) {
                return $date ? $date->format('Y-m-d'): (new \DateTime())->format('Y-m-d');
            },
            function (?string $dateString) {
                dump($dateString);
                $date = new \DateTime($dateString);
                dump($date);
                return $date;
            }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
            'block_prefix'=>''
        ]);
    }
}
