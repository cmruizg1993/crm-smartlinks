<?php

namespace App\Form;

use App\Entity\Contrato;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', NumberType::class,['attr'=>['type'=> 'number']])
            ->add('fecha', TextType::class, ['mapped'=>false,'attr'=>['class'=> 'form-control input-lg datepicker']])
            ->add('cliente', ClienteType::class)
            ->add('plan')
            ->add('vendedor')
            ->add('instalador')
            ->add('parroquia',null,['attr'=>['readonly' =>'true']])
            ->add('direccion')
            ->add('vlan')
            ->add('nodo')
            ->add('nap')
            ->add('puerto')
            ->add('pppoe')
            ->add('valorSuscripcion', NumberType::class,['attr'=>['type'=> 'number']])
            ->add('equiposjson', HiddenType::class, ['mapped'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
        ]);
    }
}
