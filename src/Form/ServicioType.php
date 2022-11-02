<?php

namespace App\Form;

use App\Entity\OpcionCatalogo;
use App\Entity\Servicio;
use App\Repository\OpcionCatalogoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicioType extends AbstractType
{
    private $repositorioOpciones;
    public function __construct(OpcionCatalogoRepository $repositorioOpciones)
    {
        $this->repositorioOpciones = $repositorioOpciones;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('nombre')
            ->add('precio')
            ->add('activo')
            ->add('inluyeIva')
            ->add('codigoPorcentaje', EntityType::class, [
                'class' => OpcionCatalogo::class,
                'choice_label' => function(OpcionCatalogo $opcionCatalogo) {
                    return sprintf('%s', $opcionCatalogo->getTexto());
                },
                'choices' => $this->repositorioOpciones->findByCodigoCatalogo('iva'),
                'choice_value' => function( $opcionCatalogo){
                    $type = gettype($opcionCatalogo);
                    if($type=='integer')
                        return $this->repositorioOpciones->findOneByCodigoyCatalogo($opcionCatalogo, 'iva')->getCodigo();
                    return $opcionCatalogo? $opcionCatalogo->getCodigo():null;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Servicio::class,
        ]);
    }
}
