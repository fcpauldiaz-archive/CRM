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
                    'INTEGER' => 'Entero',
                    'DOUBLE PRECISION' => 'Decimal',
                    'VARCHAR(50)' => 'Texto',
                    'DATE' => 'Fecha',
                    'BOOLEAN' => 'Bandera'
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
