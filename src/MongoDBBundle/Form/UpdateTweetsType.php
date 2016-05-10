<?php

namespace MongoDBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateTweetsType extends AbstractType
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
        ->add('submit', 'submit', [
                'label' => 'Actualizr',
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
