<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\TypeContrat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('ref_type_contrat', EntityType::class, [
                'class' => TypeContrat::class,
                'choice_label' => 'label', // Replace 'label' with the actual property name of TypeContrat entity to display
                'placeholder' => 'Select type of contract',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
