<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Cour;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCour', TextType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'nomCategorie'])
                ->add('valider', SubmitType::class, [
                    'attr' =>['class' => 'btn btn-dark']
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cour::class,
        ]);
    }
}
