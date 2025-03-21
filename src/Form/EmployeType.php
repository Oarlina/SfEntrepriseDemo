<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeType extends AbstractType
{
    // FormBuilder = c'est un objet. il permet de faire les champs du formulaires
    // :void = 
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // -> add rejoute un élément au formulaire, ici c'est le nom qui est du text 
            ->add('nom', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ])) // le array() est du bootstrap cela permet de styliser la ligne

            ->add('prenom', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ]))
            // ici on a un élément qui est un dateTime
            ->add('dateNaissance', null, 
            [
                'widget' => 'single_text', // single_text permet d'enrgistrer la date directement, on a choice qui donne une liste deroulant et text ou on doit inserer la date nous meme 
                'attr'=> [
                    'class' => 'form-control'
                ]
            ], DateType::class)

            ->add('dateEmbauche', null, 
            [
                'widget' => 'single_text',
                'attr'=> [
                    'class' => 'form-control'
                ]
            ], DateType::class)

            ->add('ville', TextType::class, array('attr'=> 
            [
                'class' => 'form-control'
            ]))

            ->add('entreprise', EntityType::class, 
            [
                'class' => Entreprise::class,
                'choice_label' => 'raisonSociale',
                'attr'=> [
                    'class' => 'form-control'
                ] // on dit qu'elle entity on lie et qu'est ce qu'on doit afficher 
            ])
            // on rajoute u bouton pour valider le formulaire (on peut cliquer dessus que si tous les champs du formulaires sont remplis)
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
