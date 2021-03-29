<?php

namespace App\Form;

use App\Entity\SAN;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SANType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('fecha')
            ->add('cliente')
            ->add('plan')
            ->add('vendedor')
            ->add('parroquia',null,['attr'=>['readonly' =>'true']])
            ->add('direccion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SAN::class,
        ]);
    }
}
