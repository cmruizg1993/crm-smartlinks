<?php

namespace App\Form;

use App\Entity\Colaborador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Matrix\add;

class ColaboradorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cedula')
            ->add('nombres')
            ->add('direccion')
            ->add('cargo')
            ->add('proveedores')
            ->add('parroquia')
            ->add('usuario', RegistrationFormType::class)
            ->add('ruc')
            ->add('razon')
            ->add('factura')
            ->add('iva')
            ->add('retFuente')
            ->add('retIva')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Colaborador::class,
        ]);
    }
}
