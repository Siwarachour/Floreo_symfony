<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UsernewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('email')
            ->add('datenaissance', BirthdayType::class, [
                'label' => 'Date de Naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'required' => true,
            ])
            ->add('number')
            ->add('password', PasswordType::class)
            ->add('image', FileType::class, [
                'data_class' => NULL,
                "required" => false,
                'constraints' => [
                    new File([
                        'maxSize' => '9000k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/JPEG',
                            'image/JPG',
                            'image/PNG',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_FOURNISSEUR' => 'ROLE_FOURNISSEUR',
                    'ROLE_CLIENT' => 'ROLE_CLIENT',
                ],
                'multiple' => true,
                'required' => true 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
