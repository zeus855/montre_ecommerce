<?php

namespace App\Form;

use App\Entity\Montre;
use App\Form\ImageType;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', TextareaType::class)
            ->remove('image_name')
            ->remove('updatedAt')            
            ->add('prix', MoneyType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
            ])
            ->add("imageFile", FileType::class, ["label" => "Image"]);
    }      
    
    
    //         ->add('images', CollectionType::class, [
    //             'label' => false,
    //             'entry_type' => ImageType::class,
    //             'allow_add' => true,
    //             'prototype' => true,
    //             'entry_options' => [
    //                 'attr' => ['class' => 'row'],
    //             ],
    //             'allow_delete' => true,
    //             'delete_empty' => true
    //         ]);
    // }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Montre::class,
        ]);
    }
}
