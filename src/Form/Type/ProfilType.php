<?php

namespace fayme\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('description', 'text')
            ->add('categorie', 'text')
            ->add('codePostal', 'integer', array('attr' => array('min' => 00000, 'max' => 99999,)))
            ->add('ville', 'text')
            ->add('mail', 'email')
            ->add('username', 'text')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
            ));
    }

    public function getName()
    {
        return 'user';
    }
}
