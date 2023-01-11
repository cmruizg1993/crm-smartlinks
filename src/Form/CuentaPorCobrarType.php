<?php

namespace App\Form;

use App\Entity\CuentaPorCobrar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaPorCobrarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha')
            ->add('observaciones')
            ->add('total')
            ->add('plazo')
            ->add('usuario')
            ->add('cliente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CuentaPorCobrar::class,
        ]);
    }
}
