<?php

namespace App\Form;

use App\Entity\Dechets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class DechetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('date_entre')
            ->add('quantite')
            ->add('description')
            ->add('image',FileType::class,['data_class' => NULL, "required" => false, 'constraints' => [
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
            ]])
            //->add('ReservationDechets')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dechets::class,
        ]);
    }
}
