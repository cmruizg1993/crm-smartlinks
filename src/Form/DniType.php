<?php

namespace App\Form;

use App\Entity\Dni;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DniType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero',null, [
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Ingrese un número de documento valido'
                    ])
                ]
            ])
            //->add('fecha_exp')
                /*
            ->add('foto_frontal', FileType::class,
                ['mapped'=>false,'required'=>false,'attr'=>['accept' => ".png,.jpg,.jpeg"],'constraints' => [
                    new File([
                        'maxSize' => '1024k'])]])
            ->add('foto_posterior', FileType::class,
                ['mapped'=>false,'required'=>false,'attr'=>['accept' => ".png,.jpg,.jpeg"],'constraints' => [
                    new File([
                        'maxSize' => '1024k'])]])*/
            ->add('tipo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dni::class,
        ]);
    }
}
