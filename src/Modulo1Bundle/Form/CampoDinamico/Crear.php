<?php

namespace Modulo1Bundle\Form\CampoDinamico;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class Crear extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, [
                'label' => 'Nombre campo',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('tipo', 'choice', [
                'label' => 'Tipo de valor del campo',
                'choices' => [
                    'INTEGER' => 'entero',
                    'DOUBLE PRECISION' => 'decimal',
                    'VARCHAR(50)' => 'texto',
                    'DATE' => 'fecha',
                    'BOOLEAN' => 'bandera'
                ]
            ])
            ->add('crear', 'submit', [
                'label' => 'Crear campo'
            ]);
    }

    public function getName()
    {
        return 'crear_campo_dinamico';
    }
}
