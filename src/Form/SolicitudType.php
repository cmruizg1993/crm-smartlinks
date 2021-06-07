<?php

namespace App\Form;

use App\Entity\CuentaBancaria;
use App\Entity\Solicitud;
use App\Repository\CuentaBancariaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Valid;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cliente', ClienteType::class,[
                'constraints'=>[
                    new Valid()
                ]
            ])
            ->add('formaPago')
            ->add('vendedor')
            ->add('plan')
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('cuentaBancaria',EntityType::class, [
                'class' => CuentaBancaria::class,
                'query_builder' => function (CuentaBancariaRepository $er) {
                    return $er->createQueryBuilder('c')->where('c.esCuentaEmpresarial=1');
                }
            ])
            ->add('aprobacion', FileType::class,
                ['mapped'=>false,'required'=>false,'attr'=>['accept' => ".png,.jpg,.jpeg,.pdf"],'constraints' => [
                    new File([
                        'maxSize' => '2048k'])]])
            ->add('validacion', FileType::class,
                ['mapped'=>false,'required'=>false,'attr'=>['accept' => ".png,.jpg,.jpeg,.pdf"],'constraints' => [
                    new File([
                        'maxSize' => '2048k'])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
