<?php

namespace App\Form;

use App\Entity\DetalleCuentaPorCobrar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetalleCuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cantidad')
            ->add('precio')
            ->add('subtotal')
            ->add('descuento')
            ->add('descripcion')
            ->add('esServicio')
            ->add('iva')
            ->add('producto')
            ->add('servicio')
            ->add('cuenta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetalleCuentaPorCobrar::class,
        ]);
    }
}
