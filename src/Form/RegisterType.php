<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
                    'placeholder' => 'Merci de saisir votre adress email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Votre mot de passe',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe'
                ]
            ])
            ->add('repeatPassword', PasswordType::class, [
                'label' => 'Confirmer votre mot de passe',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre mot de passe'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
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
