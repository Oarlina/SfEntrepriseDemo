<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('raisonSociale', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ]))

            ->add('dateCreation', null, [
                'widget' => 'single_text',
                'attr'=> 
                [
                    'class' => 'form-control'
                ]
            ], DateType::class)

            ->add('adresse', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ]))

            ->add('cp', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ]))

            ->add('ville', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ])
            )
            ->add('Valider', SubmitType::class, array('attr'=> 
            [
                'class' => 'btn btn-success'
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
