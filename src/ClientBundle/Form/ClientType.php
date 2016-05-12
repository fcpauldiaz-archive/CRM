<?php

namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use GeneralClientDataBundle\Form\TelefonoClienteType as TelefonoType;
use GeneralClientDataBundle\Form\DireccionClienteType as DireccionType;
use GeneralClientDataBundle\Form\CorreoClienteType as CorreoType;

class ClientType extends AbstractType
{
    private $collection;

    private $camposDinamicos;

    public function __construct(EntityManager $entityManager, array $camposDinamicos = array())
    {
        $this->collection = [];
        $em = $entityManager;
        $sql = " 
            SELECT id, tipo_membresia
            FROM tipo_membresia
            ";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        foreach($res as $r){
           $this->collection[$r["id"]] = $r["tipo_membresia"] ;
        }

        $this->camposDinamicos = $camposDinamicos;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fechaNacimiento', 'date', [
            'years' => [1990, 1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999, 2000, 2001, 2002, 2004, 2005, 2006],
            'label' => 'Fecha Nacimiento',
            'required' => true,
        ])
        ->add('nit', 'text', [
            'label' => 'Número de identificación Tributaria',
            'required' => true,
        ])
        ->add('dpi', 'text', [
            'label' => 'DPI',
            'required' => true,
        ])
        ->add('frecuente', 'checkbox', [
            'label' => 'Es cliente frecuente',
            'required' => false,
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
            'label' => 'Estado Civil',
            'required' => true,
        ])
        ->add('sexo')
        ->add('profesion')
        ->add('tipoMembresia', 'choice', [
            'choices' => $this->collection,
                'label' => false,
                'empty_value' => 'Escoja una membresía',
                'required' => false,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
        ->add('correo', 'bootstrap_collection', [
            'allow_add' => true,
            'allow_delete' => true,
            'add_button_text' => 'Agregar Correo',
            'delete_button_text' => 'Eliminar Correo',
            'sub_widget_col' => 9,
            'button_col' => 9,
            'type' => new CorreoType(),
            // these options are passed to each "email" type
            'options' => array(
                'required' => false,
                   'attr' => [
                    'placeholder' => 'Correo',
                ],

            ),
            'label' => 'Correos',
          
        ])
        ->add('telefono', 'bootstrap_collection', [
            'allow_add' => true,
            'allow_delete' => true,
            'add_button_text' => 'Agregar Telefóno',
            'delete_button_text' => 'Eliminar Telefono',
            'sub_widget_col' => 9,
            'button_col' => 3,
            'type' => new TelefonoType(),
            // these options are passed to each "email" type
            'options' => array(
                'required' => false,
                   'attr' => [
                    'placeholder' => 'Ingresar el teléfono sin guión',
                ],

            ),
            'label' => 'Telefonos',
            
        ])
        ->add('direccion', 'bootstrap_collection', [
            'allow_add' => true,
            'allow_delete' => true,
            'add_button_text' => 'Agregar Dirección',
            'delete_button_text' => 'Eliminar Dirección',
            'sub_widget_col' => 9,
            'button_col' => 3,
            'type' => new DireccionType(),
            // these options are passed to each "email" type
            'options' => array(
                'required' => false,
                   'attr' => [
                    'placeholder' => 'Ingresar dirección',
                ],

            ),
            'label' => 'Direcciones',
           
        ])
        ->add('nacionalidad')
        ->add('imageFile', 'file', [
            'label' => 'Foto del Cliente',
            'required' => false,
        ])
        ->add('twitterUsername', 'text', [
            'label' => 'Usuario de Twitter',
            'required' => true,
            'attr' => [
                'placeholder' => 'Sin arroba'
            ]
        ])
        ->add('updateTweets', 'checkbox', [
            'label' => '¿Desea guardar los tweets del cliente?',
        ]);

        foreach ($this->camposDinamicos as $campo) {
            $builder->add($campo['nombre'], $campo['tipo']);
        }

        $builder->add('submit', 'submit', [
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
