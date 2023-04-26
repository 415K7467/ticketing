<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SUPPORT' => 'ROLE_SUPPORT',
                    'ROLE_USER' => 'ROLE_USER',
                ],
            ]);

        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            fn ($rolesAsArray) => count($rolesAsArray) ? $rolesAsArray[0]: null,
            fn ($rolesAsString) => [$rolesAsString]
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}