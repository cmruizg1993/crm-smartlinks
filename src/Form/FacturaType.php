<?php

namespace App\Form;

use App\Entity\Factura;
use App\Entity\PuntoEmision;
use App\Entity\TipoComprobante;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', TextType::class, ['attr'=>['class'=>' form-control'], 'mapped'=>false])
            ->add('vence', TextType::class, ['attr'=>['class'=>' form-control'], 'mapped'=>false])
            ->add('secuencial',TextType::class, ['attr'=>['class'=>' form-control']])
            ->add('observaciones', null, ['attr'=>['class' => 'form-control']])
            ->add('referencia', null, ['attr'=>['class' => 'form-control']])
            ->add('puntoEmision', EntityType::class,
                [
                    'class' => PuntoEmision::class,
                    'mapped'=>false,
                    'attr'=>
                        [
                            'class'=>' form-control'
                        ]
                ]
            )
            ->add('tipoComprobante', EntityType::class,
                [
                    'class' => TipoComprobante::class,
                    'mapped'=>false,
                    'attr'=>
                        [
                            'class'=>' form-control'
                        ]
                ]
            )
            ->add('detalles', CollectionType::class, [
                'entry_type' => DetalleFacturaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])
            ->add('usuario', null, ['attr'=>['class' => 'form-control'] ])
            ->add('cedula', TextType::class ,['mapped'=>false, 'attr'=>['class' => 'form-control', 'minlength'=> 5, 'maxlength'=>13, 'readonly'=> true]])
            ->add('nombre',TextType::class, ['mapped'=>false, 'attr'=>['class'=>' form-control']])
            ->add('detallesjson', HiddenType::class, ['mapped'=>false])
            ->add('contrato', null, ['mapped'=>false, 'attr'=>['readonly'=>true]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
        ]);
    }
}
