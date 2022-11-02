<?php

namespace App\Form;

use App\Entity\OpcionCatalogo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpcionCatalogoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('texto')
            ->add('catalogo')
            ->add('valorNumerico')
            ->add('cssClass')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OpcionCatalogo::class,
        ]);
    }
}
