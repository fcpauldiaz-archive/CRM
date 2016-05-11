<?php

namespace MongoDBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class UpdateTweetsType extends AbstractType
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
         ->add('cliente', 'choice', [
                'choices' => $this->collection,
                'label' => false,
                'empty_value' => 'Escoja un cliente',
                'required' => false,
                'attr' => [
                    'class' => 'select2'
                ]
        ])
        ->add('submit', 'submit', [
                'label' => 'Actualizar',
                'attr' => [
                    'class' => 'btn btn-primary',
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
