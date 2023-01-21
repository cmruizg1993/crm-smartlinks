<?php

namespace App\Form;

use App\Entity\DetalleFactura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetalleFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('servicio')
            ->add('producto')
            ->add('cuota')
            ->add('esServicio')
            ->add('descripcion')
            ->add('cantidad',null, ['label'=>false, 'attr'=>['class'=>'form-control']])
            ->add('precioSinImp',null, ['label'=>false, 'attr'=>['class'=>'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetalleFactura::class,
        ]);
    }
}
