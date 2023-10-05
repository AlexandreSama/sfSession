<?php
namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextType::class)
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text', 'attr' =>['class' =>'form-control']
            
            ])
            
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text', 'attr' =>['class' =>'form-control']
            ])

            ->add('nbPlace', IntegerType::class)
            
            ->add('programmes', CollectionType::class, [
                'entry_type' => ProgrammeType::class,       
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            
            ->add('formation', EntityType::class, [
                'class' => Formation::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'nomFormation'])
            

            ->add('valider', SubmitType::class, [
                'attr' =>['class' => 'btn btn-dark']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}