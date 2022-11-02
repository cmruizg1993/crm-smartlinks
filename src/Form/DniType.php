<?php

namespace App\Form;

use App\Entity\Dni;
use App\Entity\OpcionCatalogo;
use App\Repository\OpcionCatalogoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DniType extends AbstractType
{
    private $repositorioOpciones;
    public function __construct(OpcionCatalogoRepository $repositorioOpciones)
    {
        $this->repositorioOpciones = $repositorioOpciones;
    }

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
            ->add('tipo', EntityType::class, [
                'class' => OpcionCatalogo::class,
                'mapped'=>true,
                'choice_label' => function(OpcionCatalogo $opcionCatalogo) {
                    return sprintf('%s', $opcionCatalogo->getTexto());
                },
                'choices' => $this->repositorioOpciones->findByCodigoCatalogo('tipo-doc'),
                'choice_value' => function( $opcionCatalogo){
                    return $opcionCatalogo? $opcionCatalogo->getCodigo():null;
                },
                'attr'=>['class'=>' form-control']
            ]);
        $builder->get('tipo')
            ->addModelTransformer(new CallbackTransformer(
                function (?string $codigoOpcion) {
                    // transform the string back to an array
                    $tipo = $this->repositorioOpciones->findOneByCodigoyCatalogo($codigoOpcion, 'tipo-doc');
                    return $tipo;
                },
                function (?OpcionCatalogo $opcion) {
                    // transform the array to a string
                    return $opcion ? $opcion->getCodigo(): null;
                }

            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dni::class,
        ]);
    }
}
