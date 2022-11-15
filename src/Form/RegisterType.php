<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Votre adresse email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent etre identiques',
                //'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-primary btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
