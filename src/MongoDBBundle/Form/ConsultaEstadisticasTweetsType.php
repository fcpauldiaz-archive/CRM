<?php

namespace MongoDBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
class ConsultaEstadisticasTweetsType extends AbstractType
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
       ->add('usuario', 'choice', [
                'choices' => $this->collection,
                'label' => false,
                'empty_value' => 'Escoja un cliente',
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
        ->add('hashtag', 'choice', [
                 'choices'  => array(
                    'No' => 0,
                    'Sí' => 1
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Agrupar por hashtag?',
            'required' => true,
        ])
        ->add('idioma', 'choice', [
               'choices'  => array(
                    'No' => 0,
                    'Sí' => 1
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Agrupar por idioma?',
            'required' => true,
        ])
        ->add('retweet', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Agrupar por RT y FAV por día?',
            'required' => true,
        ])
        ->add('cantidad', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Agrupar tweets por cantidad?',
            'required' => true,
        ])
         ->add('menciones', 'choice', [
                'choices'  => array(
                    'No' => 0,
                    'Sí' => 1
                ),
            // *this line is important*
            'choices_as_values' => true,
            'label' => '¿Agrupar por menciones?',
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
