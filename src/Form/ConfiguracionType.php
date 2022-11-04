<?php

namespace App\Form;

use App\Entity\Configuracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('obligadoContabilidad')
            ->add('razonSocial')
            ->add('nombreComercial')
            ->add('ruc')
            ->add('email')
            ->add('telefono')
            ->add('direccion')
            ->add('p12Name')
            ->add('p12Password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Configuracion::class,
        ]);
    }
}
