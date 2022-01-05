<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commande;
use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder  
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullname',
                'label' => 'Entrez le prenom du lecteur',
                'attr' => ['class' => 'js-example-basic-multiple']
            ])
            ->add('voyage', EntityType::class, [
                'multiple'=>true,
                'class' => Voyage::class,
                'choice_label' => 'destination',
                'label' => 'Entrez la destination',
                'attr' => ['class' => 'js-example-basic-multiple']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => ['class' => 'btn btn-success d-block mx-auto my-3 col-4']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
