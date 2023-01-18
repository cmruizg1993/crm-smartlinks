<?php

namespace App\Form;

use App\Entity\CuentaPorCobrar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaPorCobrarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', DateType::class,['input'=>'string', 'widget'=>'single_text'])
            ->add('observaciones')
            ->add('total')
            ->add('plazo')
            ->add('usuario')
            ->add('cliente')
            ->add('detalles', CollectionType::class, ['entry_type'=>DetalleCuentaType::class, 'allow_add'=>true, 'allow_delete'=>true])
        ;
        $builder->get('fecha')->addModelTransformer(new CallbackTransformer(
            function (? \DateTime $date = null) {
                return $date ? $date->format('Y-m-d'): (new \DateTime())->format('Y-m-d');
            },
            function (?string $dateString) {
                //dump($dateString);
                $date = new \DateTime($dateString);
                //dump($date);
                return $date;
            }));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CuentaPorCobrar::class,
        ]);
    }
}
