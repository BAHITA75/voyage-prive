<?php

namespace App\Form;

use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditVoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villeDepart',TextType::class, [
                'label' => 'Ville de depart',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez la ville de de depart'],
                'constraints' => [
                    new NotBlank ([
                        'message' => 'Ce champs ne peut pas etre vide'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 150,
                        'minMessage' => 'Le nom de la ville doit contenir {{ limit }} caractères au minimum.',
                        'maxMessage' => 'Le nom de la ville doit contenir {{ limit }} caractères au maximum.',
                    ])
                ]
            ])
            ->add('destination',TextType::class, [
                'label' => 'Destination',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez la ville de destination'],
                'constraints' => [
                    new NotBlank ([
                        'message' => 'Ce champs ne peut pas etre vide'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 150,
                        'minMessage' => 'Le nom de la ville doit contenir {{ limit }} caractères au minimum.',
                        'maxMessage' => 'Le nom de la ville doit contenir {{ limit }} caractères au maximum.',
                    ])
                ]
            ])
            ->add('description',TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez une description'],
                'constraints' => [
                    new NotBlank ([
                        'message' => 'Ce champs ne peut pas etre vide'
                    ]),
                ]
            ])
            ->add('dateDepart', BirthdayType::class, [
                'label' => 'Date de depart',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez la date de depart'],
                'widget' => 'single_text', 
            ])
            ->add('dateRetour', BirthdayType::class, [
                'label' => 'Date de retour',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez la date de retour'],
                'widget' => 'single_text', 
            ])
            ->add('dureeSejour', IntegerType::class, [
                'label' => 'Durée de séjour',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez le nombre de nuits'], 
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'attr' => ['placeholder' => 'Entrez un prix']
            ])
            

            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => ['class' => 'btn btn-warning d-block mx-auto my-3 col-4']
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
            'allow_file_upload' => true,
        ]);
    }
}
