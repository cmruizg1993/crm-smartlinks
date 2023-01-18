<?php

namespace App\Form;

use App\Entity\CuentaBancaria;
use App\Entity\Orden;
use App\Entity\Contrato;
use App\Repository\CuentaBancariaRepository;
use App\Repository\ContratoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateType::class,['widget'=>'single_text'])
            ->add('fechaEjecucion', DateType::class,['widget'=>'single_text'])
            ->add('codigo',null,['label'=>'Nro. de Orden'])
            ->add('tipo')
            //->add('Contrato', HiddenType::class)

            ->add('Contrato',EntityType::class, [
                'class' => Contrato::class,
                'query_builder' => function (ContratoRepository $er) {
                    return $er->createQueryBuilder('s');
                }
            ])
            ->add('estado')
            ->add('tecnico')
            ->add('observaciones');
        /*
        $builder->get('fecha')->addModelTransformer(new CallbackTransformer(
            function (? \DateTime $date = null) {
                return $date ? $date->format('Y-m-d'): (new \DateTime())->format('Y-m-d');
            },
            function (?string $dateString) {
                //dump($dateString);
                $date = new \DateTime($dateString);
                //dump($date);
                return $date;
            }));*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orden::class,
        ]);
    }
}
