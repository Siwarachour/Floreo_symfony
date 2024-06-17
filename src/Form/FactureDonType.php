<?php

namespace App\Form;

use App\Entity\FactureDon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureDonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_donateur')
            ->add('prenom_donateur')
            ->add('email')
            ->add('adresses')
            ->add('numero_telephone')
            ->add('description')
            ->add('don')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactureDon::class,
        ]);
    }
}
