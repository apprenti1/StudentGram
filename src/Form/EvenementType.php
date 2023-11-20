<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('date')
            ->add('heure')
            ->add('duree')
            ->add('ref_salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'label',
                'placeholder' => 'Select un evenement',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
