<?php

namespace App\Form;

use App\Entity\Factura;
use App\Entity\OpcionCatalogo;
use App\Entity\PuntoEmision;
use App\Entity\TipoComprobante;
use App\Repository\OpcionCatalogoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('detallesjson', HiddenType::class, ['mapped'=>false])
            /*
            ->add('tipoAmbiente', EntityType::class, [
                'class' => OpcionCatalogo::class,
                'mapped'=>false,
                'choice_label' => function(OpcionCatalogo $opcionCatalogo) {
                    return sprintf('%s', $opcionCatalogo->getTexto());
                },
                'choices' => $this->repositorioOpciones->findByCodigoCatalogo('ambiente'),
                'choice_value' => function( $opcionCatalogo){
                    return $opcionCatalogo? $opcionCatalogo->getCodigo():null;
                },
                'attr'=>['class'=>' form-control']
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
        ]);
    }
}
