<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new Length(['min' => 2, 'max' => 16],
                        minMessage: "vous n'utilisez pas assez de caractère",
                        maxMessage: "vous utilisez trop de caractères")
                ]
            ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('role', ChoiceType::class, [
                'mapped' => false,
                'placeholder' => 'choose an option',
                'choices' => [
                    'Aucun' => null,
                    'Admin' => 'ROLE_ADMIN',

                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
