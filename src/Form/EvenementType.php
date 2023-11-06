<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Salle;

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

            ->add('salle', EntityType::class, [
                'class' => Salle::class, // Spécifiez la classe de l'entité Salle
                'choice_label' => 'nom', // Le champ à afficher dans la liste déroulante (par exemple, le nom de la salle)
                'label' => 'Salle', // Étiquette du champ
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
