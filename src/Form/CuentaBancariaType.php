<?php

namespace App\Form;

use App\Entity\CuentaBancaria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaBancariaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('tipoCuenta',ChoiceType::class,[
                'choices'=>[
                    'CTA. DE AHORROS'=>'A',
                    'CTA. CORRIENTE'=>'C'
                ]
            ])
            ->add('cedula')
            ->add('beneficiario')
            ->add('banco')
            ->add('esCuentaEmpresarial')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CuentaBancaria::class,
        ]);
    }
}
