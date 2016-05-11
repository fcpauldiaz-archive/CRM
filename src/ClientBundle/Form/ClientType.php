<?php

namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ClientType extends AbstractType
{
   

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fechaNacimiento', 'date', [
            'label' => 'Fecha Nacimiento',
            'required' => true,
        ])
        ->add('NIT', 'text', [
            'label' => 'Número de identificación Tributaria',
            'required' => true,
        ])
        ->add('frecuente', 'checkbox', [
            'label' => 'Es cliente frecuente',
            'required' => true,
        ])
        ->add('nombres', 'text', [
            'label' => 'Nombre/s',
            'required' => true,
        ])
        ->add('apellidos', 'text', [
            'label' => 'Apellidos',
            'required' => true,
        ])
        ->add('estadoCivil', 'text', [
            'label' => 'estado Civil',
            'required' => true,
        ])
        ->add('imageFile', 'file', [
            'label' => 'Foto del Cliente',
            'required' => true,
        ])
        ->add('submit', 'submit', [
                'label' => 'Guardar',
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
        return 'cliente';
    }
}
