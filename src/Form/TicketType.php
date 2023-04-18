<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class)
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Haute' => 1,
                    'Moyenne' => 2,
                    'Faible' => 3
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Nouveau' => 1,
                    'En cours' => 2,
                    'Résolue' => 3
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
