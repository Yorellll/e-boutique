<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Description')
            ->add('PriceHT')
            ->add('Available')
            ->add('img', FileType::class, [
                'label' => 'Image',
                'mapped' => false, // Ne mappez pas directement ce champ à l'entité Product
                'required' => false, // Le champ n'est pas obligatoire
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class, // Chemin de votre entité Category
                'label' => 'Catégorie', // Étiquette du champ "Catégorie"
                'choice_label' => 'name', // Champ de l'entité Category à afficher dans la liste déroulante
                'placeholder' => 'Choisissez une catégorie', // Texte de l'option vide
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
