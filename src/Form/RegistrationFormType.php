<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entreprise', TextType::class, [
                'label' => 'Entreprise',
                'required' => true,
                'attr' => ['placeholder' => 'Nom de votre entreprise'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['placeholder' => 'Votre adresse email'],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez votre adresse'],
            ])
            ->add('nif', TextType::class, [
                'label' => 'NIF',
                'required' => false,
                'attr' => ['placeholder' => 'Numéro NIF'],
            ])
            ->add('stat', TextType::class, [
                'label' => 'STAT',
                'required' => false,
                'attr' => ['placeholder' => 'Numéro STAT'],
            ])
            ->add('cif', TextType::class, [
                'label' => 'CIF',
                'required' => false,
                'attr' => ['placeholder' => 'Numéro CIF'],
            ])
            ->add('rcs', TextType::class, [
                'label' => 'RCS',
                'required' => false,
                'attr' => ['placeholder' => 'Numéro RCS'],
            ])
            ->add('rc', TextType::class, [
                'label' => 'RC',
                'required' => false,
                'attr' => ['placeholder' => 'Numéro RC'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Votre mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'mapped' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'Vous devez accepter les conditions générales.',
                    ]),
                ],
                'label' => 'J\'accepte les conditions générales',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
