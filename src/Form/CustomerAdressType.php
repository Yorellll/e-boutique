<?php

namespace App\Form;

use App\Entity\CustomerAdress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type')
            ->add('Address')
            ->add('Cp')
            ->add('City')
            ->add('Country')
            ->add('Name', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('FirstName', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('Phone', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerAdress::class,
        ]);
    }
}
