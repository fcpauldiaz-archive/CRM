<?php

namespace MongoDBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultaTweetsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('usuario', 'entity', [
                'class' => 'UserBundle:Usuario',
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'select2'
                ]
        ])
        ->add('fechaInicial', 'date', [
                'label' => 'Fecha inicio',
                'required' => false,
        ])
        ->add('fechaFinal', 'date', [
                'label' => 'Fecha final',
                'required' => false,
        ])
        ->add('imagen', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Mostrar solo los tweets con multimedia?',
            'required' => false,
        ])
         ->add('estadisticas', 'choice', [
                'choices'  => array(
                    'Sí' => 0,
                    'No' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Incluir estadísticas?',
            'required' => false,
        ])
        ->add('submit', 'submit', [
                'label' => 'Buscar',
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ],

            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tweets';
    }
}
