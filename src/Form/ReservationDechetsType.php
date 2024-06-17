<?php

namespace App\Form;

use App\Entity\ReservationDechets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReservationDechetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('dechets')
            ->add('quantite')
            ->add('nom_fournisseur')
            ->add('numero_tell')
            ->add('date', DateType::class, [
                'widget' => 'single_text', // Use a single text input
                // 'format' => 'yyyy-MM-dd', // Uncomment if needed, matching the input format
                'input' => 'datetime', // Ensure the input is a DateTime object
            ])
            ->add('date_ramassage', DateType::class, [
                'widget' => 'single_text',
                // 'format' => 'yyyy-MM-dd', // Uncomment if needed
                'input' => 'datetime',
            ]);
            //->add('User')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationDechets::class,
        ]);
    }
}
