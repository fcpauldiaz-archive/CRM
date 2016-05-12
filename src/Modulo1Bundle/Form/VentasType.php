<?php

namespace Modulo1Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use ClientBundle\Entity\Client;

class VentasType extends AbstractType
{
   
    private $collection;
     private $collection2;
    public function __construct(EntityManager $entityManager, $type = false)
    {
        $this->tipo = $type;
        $this->collection = [];
        $em = $entityManager;
        $sql = " 
            SELECT id,nombres, apellidos
            FROM client u
            ";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        foreach($res as $r){
           $this->collection[$r["id"]] = $r["nombres"].' '.$r["apellidos"];
        }

         $this->collection2 = [];
        $em = $entityManager;
        $sql = " 
            SELECT id,producto
            FROM producto u
            ";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        foreach($res as $r){
           $this->collection2[$r["id"]] = $r["producto"];
        }
        

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         if ($this->tipo){
            $builder
                ->add('cliente', 'choice', [
                        'choices' => $this->collection,
                        'label' => false,
                        'empty_value' => 'Escoja un cliente',
                        'required' => false,
                        'attr' => [
                            'class' => 'select2'
                        ],
                       
                ])
                 ->add('producto', 'choice', [
                    'choices' => $this->collection2,
                    'label' => false,
                    'empty_value' => 'Escoja un producto',
                    'required' => false,
                    'attr' => [
                        'class' => 'select2'
                    ],
            ]);
        }
        if (!$this->tipo) {
            $builder
                ->add('cliente', 'choice', [
                        'choices' => $this->collection,
                        'label' => false,
                        'empty_value' => 'Escoja un cliente',
                        'required' => false,
                        'attr' => [
                            'class' => 'select2'
                        ],
                        'disabled' => true,
                ])
                ->add('producto', 'choice', [
                    'choices' => $this->collection2,
                    'label' => false,
                    'empty_value' => 'Escoja un producto',
                    'required' => false,
                    'attr' => [
                        'class' => 'select2'
                    ],
                    'disabled' => true,
            ]); 
        }
        $builder
        ->add('cantidad', 'integer', [
            'label' => 'Cantidad',
            'required' => true,
        ])
        ->add('total', 'number', [
            'label' => 'Total',
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
        return 'direccion';
    }
}