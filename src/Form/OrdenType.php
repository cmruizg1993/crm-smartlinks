<?php

namespace App\Form;

use App\Entity\Orden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('codigo',null,['label'=>'Nro. de Orden'])
            ->add('tipo')
            ->add('san')
            ->add('estado')
            ->add('tecnico')
            ->add('observaciones')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orden::class,
        ]);
    }
}
