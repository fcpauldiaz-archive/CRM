<?php

namespace Modulo1Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use ClientBundle\Entity\Client;

class ConsultaSQLEstadisticaType extends AbstractType
{

    private $collection;
    public function __construct(EntityManager $entityManager)
    {
        $this->collection = [];
        $em = $entityManager;
        $sql = " 
            SELECT u.id, u.nombres, u.apellidos,
                 u.twitter_username
            FROM client u
            ";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        foreach($res as $r){
           $this->collection[$r["id"]] = $r["nombres"].' '.$r["apellidos"].'-  @'.$r["twitter_username"] ;
        }
        

    }


    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fechaInicial', 'date', [
                'label' => 'Fecha inicio',
                'required' => true,
        ])
        ->add('fechaFinal', 'date', [
                'label' => 'Fecha final',
                'required' => true,
        ])
        ->add('ventas_fecha', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => 'Ventas por fecha',
            'required' => true,
        ])
         ->add('clientes_fecha', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => 'clientes fecha',
            'required' => true,
        ])
       ->add('ventas_totales', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => 'ventas totales',
            'required' => true,
        ])
        ->add('productos_vendidos', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => 'productos vendidos',
            'required' => true,
        ])
        ->add('producto_total_fecha', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1,
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => 'productos totales fecha',
            'required' => true,
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
