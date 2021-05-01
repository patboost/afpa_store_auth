<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('LastName')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => "ROLE_USER",
                    'Administrateur' => "ROLE_ADMIN"
                ],
                'expanded' => true, // if false => affiche une liste
                                    // if multiple=true => checkboxes
                'multiple' => true,
                'label' => "Roles"
            ])
            ->add('password', PasswordType::class)
            ->add('confirm', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
