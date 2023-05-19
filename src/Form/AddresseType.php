<?php

namespace App\Form;

use App\Entity\Addresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('streetNumber')
            ->add('street')
            ->add('zipcode')
            ->add('city')
            ->add('country')
            ->add('phoneNumber')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addresse::class,
        ]);
    }
}
