<?php

namespace App\Form;

use App\Constante\AdresseTypeConstante;
use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => '0605992488'
                ]
            ])
            ->add('rue', TextType::class, [
                'attr' => [
                    'placeholder' => '13 rue pasteur'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'attr' => [
                    'placeholder' => '75000'
                ]
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'placeholder' => 'Paris'
                ]
            ])
            ->add('pays', TextType::class, [
                'attr' => [
                    'placeholder' => 'France'
                ],
                'required' => false
            ])
            ->add('label', ChoiceType::class, [
                'choices' => AdresseTypeConstante::MAP,
                'label' => 'Choisir Livraison ou Facturation...'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
