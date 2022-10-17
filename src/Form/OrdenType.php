<?php

namespace App\Form;

use App\Entity\CuentaBancaria;
use App\Entity\Orden;
use App\Entity\Contrato;
use App\Repository\CuentaBancariaRepository;
use App\Repository\ContratoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('Contrato',EntityType::class, [
                'class' => Contrato::class,
                'query_builder' => function (ContratoRepository $er) {
                    return $er->createQueryBuilder('s');
                }
            ])
            ->add('estado')
            ->add('tecnico')
            ->add('observaciones')
            ->add('fechaEjecucion')
            ->add('serialModem')
            ->add('serialRadio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orden::class,
        ]);
    }
}
