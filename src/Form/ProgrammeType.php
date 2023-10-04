<?php

namespace App\Form;

use App\Entity\Cour;
use App\Entity\Programme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbJours', IntegerType ::class , [ //
                'label' => 'Durée en jours',
                'attr' => ['min' => 1, 'max' => 50]
            ])
            ->add('cour', EntityType::class, [
                'label' => 'Cour',
                'class' => Cour::class, 
                'attr' => ['class' => 'form-control'],                         // Particlarité ici le type à besoin d'un tableau d'arguments pour fonctionner
                'choice_label' => 'denomination'
            ])
            ->add('session', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
