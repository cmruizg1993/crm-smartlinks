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
            ->add('servicio')
            ->add('producto')
            ->add('esServicio')
            ->add('descripcion')
            ->add('cantidad',null, ['label'=>false, 'attr'=>['class'=>'form-control']])
            ->add('precio',null, ['label'=>false, 'attr'=>['class'=>'form-control']])
            ->add('subtotal',null, ['label'=>false, 'attr'=>['class'=>'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetalleFactura::class,
        ]);
    }
}
