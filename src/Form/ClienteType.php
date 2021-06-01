<?php

namespace App\Form;

use App\Entity\Cliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres')
            ->add('email')
            ->add('telefono')
            ->add('telefonoFijo')
            ->add('fechaNacimiento',DateType::Class, array(
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-100),))
            ->add('fingerprint')
            ->add('estadoCivil')
            ->add('genero', ChoiceType::class,[
                'choices'=>[
                    'MASCULINO'=>'M',
                    'FEMENINO'=>'F'
                ]
            ])
            ->add('nacionalidad')
            ->add('residencia')
            ->add('direccion')
            ->add('dni', DniType::class)
            ->add('otro', FileType::class,
                ['mapped'=>false,'required'=>false,'attr'=>['accept' => ".png,.jpg,.jpeg,.pdf"],'constraints' => [
                    new File([
                        'maxSize' => '2048k'])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }
}
