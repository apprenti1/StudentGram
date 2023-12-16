<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextType::class)
            ->add('date', null, ['widget' => 'single_text'])
            ->add('heure', null, ['hours' => [18, 19, 20, 21, 22, 23]])
            ->add('duree', TimeType::class, [
                'widget' => 'choice', // This option renders the time as a dropdown list
                'label' => 'Durée',
                'required' => true,
            ]);
        if ($options['is_admin']) {
            $builder->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nom',
                'placeholder' => 'Selectionner une salle',
            ])
                ->add('valide', CheckboxType::class, [
                    'label' => 'Valide',
                'required' => false,
                ]);


        }
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
            'is_admin' => false,
        ]);
        $resolver->setAllowedTypes('is_admin', 'bool');
    }
}
