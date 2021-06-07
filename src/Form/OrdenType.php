<?php

namespace App\Form;

use App\Entity\CuentaBancaria;
use App\Entity\Orden;
use App\Entity\SAN;
use App\Repository\CuentaBancariaRepository;
use App\Repository\SANRepository;
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
            ->add('san',EntityType::class, [
                'class' => SAN::class,
                'query_builder' => function (SANRepository $er) {
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
