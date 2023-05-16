<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ImageType;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('categorie', EntityType::class, [
                'class'=>Categorie::class,
                'choice_label'=>'name',
                'query_builder' => function (CategorieRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('cat')
                        ->orderBy('cat.name', 'ASC');
                },

            ])
            ->add('images', CollectionType::class, [
                'entry_type'=>ImageType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'required'=>true,
                'by_reference'=>false,
                'disabled'=>false,
                'prototype'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
