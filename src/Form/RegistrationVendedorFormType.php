<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;

class RegistrationVendedorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(
                        ['message'=>'The email \'{{ value }}\' is not a valid email.']
                    ),
                    new NotBlank(
                        ['message'=>'Please enter an email.']
                    )
                ],
            ])
            ->add('phone', TelType::class,[
                'constraints' => [
                    new NotBlank([
                        'message'=>'Please enter a phone number'
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Please enter a valid phone number with min {{limit}} numbers',
                        'maxMessage' => 'Please enter a valid phone number with max {{limit}} numbers',
                        'max' => 12
                    ]),
                    new Regex([
                        "pattern"=>"/^[0-9]*$/",
                        "message"=>"Phone number must contain only numbers ex: 593988116697"
                    ])
                ]
            ])
            ->add('colaborador', VendedorType::class, [
                //'required' => false
                'constraints' => [
                    new Valid()
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                //'required' => false
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
