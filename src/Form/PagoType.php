<?php

namespace App\Form;

use App\Entity\Pago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valor')
            ->add('nroDocumento')
            ->add('ctaBancaria')
            ->add('captura', FileType::class,
                ['mapped'=>false,'required'=>false,'attr'=>['accept' => ".png,.jpg,.jpeg,.pdf"],'constraints' => [
                    new File([
                        'maxSize' => '2048k'])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pago::class,
        ]);
    }
}
